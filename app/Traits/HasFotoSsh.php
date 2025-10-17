<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

trait HasFotoSsh
{
    /** PNG 1x1 transparente como último recurso */
    protected function transparentPngBytes(): string
    {
        return base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQIW2P8/5+hHgAHvQJ9Cq2cxwAAAABJRU5ErkJggg==',
            true
        ) ?: '';
    }

    /** Caminho (local) do placeholder. Pode sobrescrever via PHOTO_PLACEHOLDER */
    protected function fotoPlaceholderPath(): string
    {
        $rel = env('PHOTO_PLACEHOLDER', 'assets/images/icons/no_image.png');
        return public_path($rel);
    }

    /** Base remota dentro do SFTP disk. Pode sobrescrever via SFTP_REMOTE_BASE (ex.: gsip/images) */
    protected function fotoRemoteBase(): string
    {
        $base = env('SFTP_REMOTE_BASE', 'gsip/images');
        return rtrim($base, '/');
    }

    /** Converte bytes em data-URI detectando mime quando possível */
    protected function bytesToDataUri(string $bytes, ?string $mime = null): string
    {
        if ($mime === null && function_exists('finfo_buffer')) {
            $f = @finfo_open(FILEINFO_MIME_TYPE);
            if ($f) {
                $det = @finfo_buffer($f, $bytes) ?: null;
                @finfo_close($f);
                if ($det) $mime = $det;
            }
        }
        $mime ??= 'image/jpeg';
        return 'data:' . $mime . ';base64,' . base64_encode($bytes);
    }

    /** Infere MIME pela extensão (PHP 8+) */
    protected function inferMimeByFilename(?string $filename): ?string
    {
        if (!$filename) return null;
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        return match ($ext) {
            'jpg', 'jpeg', 'jpe' => 'image/jpeg',
            'png'                 => 'image/png',
            'webp'                => 'image/webp',
            'gif'                 => 'image/gif',
            'bmp'                 => 'image/bmp',
            default               => null,
        };
    }

    /**
     * Resolve o id legado da pessoa neste model.
     * Pode sobrescrever criando getLegacyPessoaIdForFoto().
     */
    protected function resolveLegacyPessoaId(): ?int
    {
        if (method_exists($this, 'getLegacyPessoaIdForFoto')) {
            $val = $this->getLegacyPessoaIdForFoto();
            return $val ? (int) $val : null;
        }

        foreach (['idpessoa', 'id', 'pessoa_id'] as $key) {
            $v = $this->{$key} ?? null;
            if (!is_null($v) && (int)$v > 0) return (int)$v;
        }
        return null;
    }

    /**
     * Tenta carregar a foto. Se não existir em lugar nenhum, retorna NULL.
     * @return array{bytes:string, mime:string}|null
     */
    public function fotoSshBytesOrNull(): ?array
    {
        $idpessoa = $this->resolveLegacyPessoaId();
        if (!$idpessoa) return null;

        // Cache leve (1h)
        $cacheKey = 'pessoa_foto_bytes_or_null:' . class_basename($this) . ':' . $idpessoa;

        return Cache::remember($cacheKey, now()->addMinutes(60), function () use ($idpessoa) {
            try {
                // 0) Foto inline no próprio registro (campos img/type em base64)
                $imgInline  = $this->getAttribute('img') ?? null;   // base64 sem prefixo
                $typeInline = $this->getAttribute('type') ?? null;  // image/*
                if (!is_null($imgInline)) {
                    $decoded = base64_decode((string)$imgInline, true) ?: '';
                    return ['bytes' => $decoded, 'mime' => $typeInline ?: 'image/jpeg'];
                }

                // 1) visitante_foto (prioridade)
                $foto = DB::connection('siapen')
                    ->table('visitante_foto')
                    ->where('idvisitante', $idpessoa)
                    ->where('idposicao', 1)
                    ->orderByDesc('arquivo')
                    ->first();

                $branch  = null;
                $arquivo = null;

                if ($foto && !empty($foto->arquivo)) {
                    $branch  = 'visitante';
                    $arquivo = $foto->arquivo;
                } else {
                    // 2) interno_foto (fallback) a partir do idpessoa
                    $interno = DB::connection('siapen')
                        ->table('interno')
                        ->select('idinterno')
                        ->where('idpessoa', $idpessoa)
                        ->orderBy('idinterno')
                        ->first();

                    if ($interno) {
                        $foto = DB::connection('siapen')
                            ->table('interno_foto')
                            ->where('idinterno', $interno->idinterno)
                            ->where('idposicao', 1)
                            ->orderByDesc('arquivo')
                            ->first();

                        if ($foto && !empty($foto->arquivo)) {
                            $branch  = 'interno';
                            $arquivo = $foto->arquivo;
                        }
                    }
                }

                if (!$branch || !$arquivo) {
                    return null;
                }

                // 3) Tenta múltiplas pastas prováveis
                $subdirs = $branch === 'visitante'
                    ? ['vinculos', 'visitantes']
                    : ['custodiados', 'internos'];

                $base = $this->fotoRemoteBase();
                foreach ($subdirs as $dir) {
                    $remote = $base . '/' . $dir . '/' . $arquivo;

                    // Se seu provedor SFTP não suporta exists(), pode pular a verificação
                    if (Storage::disk('sftp')->exists($remote)) {
                        $bytes = Storage::disk('sftp')->get($remote);
                        if ($bytes) {
                            $mime = $this->inferMimeByFilename($arquivo) ?? 'image/jpeg';
                            return ['bytes' => $bytes, 'mime' => $mime];
                        }
                    }
                }

                return null;
            } catch (\Throwable $e) {
                return null;
            }
        });
    }

    /**
     * Igual ao anterior, porém com placeholder garantido.
     * @return array{bytes:string, mime:string}
     */
    public function fotoSshBytes(): array
    {
        $img = $this->fotoSshBytesOrNull();
        if ($img) return $img;

        $phPath  = $this->fotoPlaceholderPath();
        $phBytes = is_file($phPath)
            ? ((string) @file_get_contents($phPath))
            : $this->transparentPngBytes();
        $phMime  = @mime_content_type($phPath) ?: 'image/png';

        return ['bytes' => $phBytes, 'mime' => $phMime];
    }

    /** Data-URI sempre presente (usa placeholder quando necessário) */
    public function fotoSsh(): string
    {
        $data = $this->fotoSshBytes();
        return $this->bytesToDataUri($data['bytes'], $data['mime']);
    }

    /**
     * Accessor usado como $model->foto:
     * - retorna data-URI quando existir foto
     * - retorna NULL quando não existir (facilita fallback na Blade)
     */
    public function getFotoAttribute(): ?string
    {
        $img = $this->fotoSshBytesOrNull();
        return $img ? $this->bytesToDataUri($img['bytes'], $img['mime']) : null;
    }
}
