<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

trait HasFotoSsh
{
    /**
     * Caminho do placeholder (pode sobrescrever via .env PHOTO_PLACEHOLDER)
     */
    protected function fotoPlaceholderPath(): string
    {
        $rel = env('PHOTO_PLACEHOLDER', 'img/avatar5.png');
        return public_path($rel);
    }

    /**
     * Base remota (relativa ao SFTP_ROOT), ex.: "gsip/images"
     * Pode sobrescrever via .env SFTP_REMOTE_BASE
     */
    protected function fotoRemoteBase(): string
    {
        $base = env('SFTP_REMOTE_BASE', 'gsip/images');
        return rtrim($base, '/');
    }

    /**
     * Converte bytes binários em data-uri (detecta MIME sempre que possível)
     */
    protected function bytesToDataUri(string $bytes, ?string $mime = null): string
    {
        if ($mime === null && function_exists('finfo_buffer')) {
            $f = @finfo_open(FILEINFO_MIME_TYPE);
            if ($f) {
                $detected = @finfo_buffer($f, $bytes) ?: null;
                @finfo_close($f);
                if ($detected) {
                    $mime = $detected;
                }
            }
        }
        if ($mime === null) {
            $mime = 'image/jpeg';
        }

        return 'data:' . $mime . ';base64,' . base64_encode($bytes);
    }

    /**
     * Inferência simples de MIME pela extensão do arquivo
     */
    protected function inferMimeByFilename(?string $filename): ?string
    {
        if (!$filename) {
            return null;
        }

        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        switch ($ext) {
            case 'jpg':
            case 'jpeg':
            case 'jpe':
                return 'image/jpeg';
            case 'png':
                return 'image/png';
            case 'webp':
                return 'image/webp';
            case 'gif':
                return 'image/gif';
            case 'bmp':
                return 'image/bmp';
            default:
                return null;
        }
    }

    /**
     * Descobre o ID legado da pessoa no model atual.
     * Funciona com idpessoa, id, pessoa_id — ou método customizado no model.
     */
    protected function resolveLegacyPessoaId(): ?int
    {
        // Model pode implementar isso para controle total
        if (method_exists($this, 'getLegacyPessoaIdForFoto')) {
            $val = $this->getLegacyPessoaIdForFoto();
            return $val ? (int) $val : null;
        }

        foreach (['idpessoa', 'id', 'pessoa_id'] as $key) {
            if (isset($this->{$key}) && $this->{$key}) {
                return (int) $this->{$key};
            }
        }

        return null;
    }

    /**
     * Acesso principal: retorna SEMPRE uma data-URI (foto ou placeholder)
     */
    public function fotoSsh(): string
    {
        // Placeholder seguro
        $placeholder = $this->fotoPlaceholderPath();
        $defaultDataUrl = is_file($placeholder)
            ? $this->bytesToDataUri((string) @file_get_contents($placeholder), @mime_content_type($placeholder) ?: 'image/png')
            : 'data:image/png;base64,';

        // Resolve idpessoa
        $idpessoa = $this->resolveLegacyPessoaId();
        if (!$idpessoa) {
            return $defaultDataUrl;
        }

        $cacheKey = 'pessoa_foto:' . class_basename($this) . ':' . $idpessoa;

        return Cache::remember($cacheKey, now()->addMinutes(60), function () use ($idpessoa, $defaultDataUrl) {
            try {
                // 1) Se o model tiver img/type (base64 no próprio registro), usa direto
                $hasImgAttr = (
                    property_exists($this, 'img') ||
                    (property_exists($this, 'attributes') && is_array($this->attributes ?? []) && array_key_exists('img', $this->attributes))
                );

                if ($hasImgAttr) {
                    $img  = $this->img ?? null;
                    $type = $this->type ?? 'image/jpeg';
                    if (!is_null($img)) {
                        return 'data:' . $type . ';base64,' . $img;
                    }
                }

                // 2) visitante_foto (idposicao=1) — mais recente
                $foto = DB::connection('siapen')
                    ->table('visitante_foto')
                    ->where('idvisitante', $idpessoa)
                    ->where('idposicao', 1)
                    ->orderByDesc('arquivo')
                    ->first();

                $subdir = null;

                if ($foto) {
                    $subdir = 'vinculos';
                } else {
                    // 3) interno_foto (fallback)
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

                        if ($foto) {
                            $subdir = 'custodiados';
                        }
                    }
                }

                if (!$foto || empty($foto->arquivo) || !$subdir) {
                    return $defaultDataUrl;
                }

                // 4) Caminho no SFTP (relativo ao root configurado)
                $base       = $this->fotoRemoteBase();
                $remotePath = $base . '/' . $subdir . '/' . $foto->arquivo;

                // (Opcional) existência
                // if (!Storage::disk('sftp')->exists($remotePath)) return $defaultDataUrl;

                // 5) Lê bytes via SFTP e gera data-uri
                $bytes = Storage::disk('sftp')->get($remotePath);
                if ($bytes) {
                    $mimeGuess = $this->inferMimeByFilename($foto->arquivo);
                    return $this->bytesToDataUri($bytes, $mimeGuess);
                }

                return $defaultDataUrl;
            } catch (\Throwable $e) {
                return $defaultDataUrl;
            }
        });
    }

    /**
     * Accessor opcional: permite $model->foto
     */
    public function getFotoAttribute(): string
    {
        return $this->fotoSsh();
    }
}
