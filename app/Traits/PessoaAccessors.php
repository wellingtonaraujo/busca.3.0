<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use App\Models\Custodiado\PessoaDocumento;
use App\Models\Custodiado\PessoaEndereco;
use App\Models\Custodiado\DocumentoTipo;
use App\Models\Custodiado\PessoaFoto;

/**
 * Trait com computed attributes e utilidades da Pessoa.
 * Mantém a model Pessoa enxuta.
 */
trait PessoaAccessors
{
    /** Memo simples por request */
    protected array $__pessoa_memo = [];

    /** Defaults caso não defina na model */
    public const DOC_TIPO_CPF_DEFAULT = 2;
    public const DOC_TIPO_RG_DEFAULT  = 1;

    /* =========================
     |  NASCIMENTO / IDADE
     |=========================*/

    /** GET exibível dd/mm/YYYY */
    public function getNascimentoBrAttribute(): ?string
    {
        $nasc = $this->getAttribute('nascimento');
        if (blank($nasc)) return null;
        try {
            $dt = $nasc instanceof Carbon ? $nasc : Carbon::parse($nasc);
            return $dt->format('d/m/Y');
        } catch (\Throwable $e) {
            return null;
        }
    }

    /** SET flexível para 'nascimento' aceitando dd/mm/YYYY */
    public function setNascimentoAttribute($value): void
    {
        if (blank($value)) {
            $this->attributes['nascimento'] = null;
            return;
        }
        if ($value instanceof Carbon) {
            $this->attributes['nascimento'] = $value->format('Y-m-d');
            return;
        }
        try {
            if (Carbon::hasFormat($value, 'd/m/Y')) {
                $this->attributes['nascimento'] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
            } else {
                $this->attributes['nascimento'] = Carbon::parse($value)->format('Y-m-d');
            }
        } catch (\Throwable $e) {
            $this->attributes['nascimento'] = $value; // último recurso
        }
    }

    /** Idade numérica */
    public function getIdadeAttribute(): ?int
    {
        $nasc = $this->getAttribute('nascimento');
        if (blank($nasc)) return null;
        try {
            $dt = $nasc instanceof Carbon ? $nasc : Carbon::parse($nasc);
            return $dt->age;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /** Idade como texto "X anos" */
    public function getIdadeTextoAttribute(): ?string
    {
        $anos = $this->idade;
        return $anos === null ? null : $anos . ' ' . ($anos === 1 ? 'ano' : 'anos');
    }

    /* =========================
     |  DOCUMENTOS (CPF / RG)
     |=========================*/

    /** Resolve id do tipo (ex.: 'CPF') na conexão do DocumentoTipo (siapenweb). Cache 1h. */
    protected static function resolveDocTipoId(string $abrev, int $fallback): int
    {
        return Cache::remember("doc_tipo_id:{$abrev}", 3600, function () use ($abrev, $fallback) {
            if (!class_exists(DocumentoTipo::class)) return $fallback;
            $conn = (new DocumentoTipo)->getConnectionName();
            $id = DocumentoTipo::on($conn)->where('abreviatura', $abrev)->value('id');
            return $id ?: $fallback;
        });
    }

    public function getCpfAttribute(): ?string
    {
        $tipoId = \defined(static::class . '::DOC_TIPO_CPF')
            ? \constant(static::class . '::DOC_TIPO_CPF')
            : self::resolveDocTipoId('CPF', self::DOC_TIPO_CPF_DEFAULT);

        $conn = (new PessoaDocumento)->getConnectionName();

        $doc = PessoaDocumento::on($conn)
            ->select(['documento_numero', 'data_expedicao', 'id'])
            ->where('pessoa_id', $this->getKey())
            ->where('documento_tipo_id', $tipoId)
            ->orderByRaw('COALESCE(data_expedicao, "0000-00-00") DESC')
            ->orderByDesc('id')
            ->first();

        if (!$doc || blank($doc->documento_numero)) return null;

        $digits = preg_replace('/\D+/', '', (string) $doc->documento_numero);
        if (strlen($digits) !== 11) return $doc->documento_numero;

        return substr($digits, 0, 3) . '.'
            . substr($digits, 3, 3) . '.'
            . substr($digits, 6, 3) . '-'
            . substr($digits, 9, 2);
    }

    public function getRgAttribute(): ?string
    {
        $tipoId = \defined(static::class . '::DOC_TIPO_RG')
            ? \constant(static::class . '::DOC_TIPO_RG')
            : self::resolveDocTipoId('RG', self::DOC_TIPO_RG_DEFAULT);

        $conn = (new PessoaDocumento)->getConnectionName();

        $doc = PessoaDocumento::on($conn)
            ->select(['documento_numero', 'expedicao_estado_id', 'data_expedicao', 'id'])
            ->where('pessoa_id', $this->getKey())
            ->where('documento_tipo_id', $tipoId)
            ->orderByRaw('COALESCE(data_expedicao, "0000-00-00") DESC')
            ->orderByDesc('id')
            ->first();

        if (!$doc || blank($doc->documento_numero)) return null;

        $numero = preg_replace('/[^0-9A-Za-z]/', '', (string) $doc->documento_numero);
        // se o accessor existir, aproveita (evita nova query):
        $uf = method_exists($doc, 'getExpedicaoEstadoAttribute') ? $doc->expedicao_estado : null;

        return $uf ? "{$numero}-{$uf}" : $numero;
    }

    /* =========================
     |  ENDEREÇO
     |=========================*/

    /** Model do endereço “atual” (último por id), com fallback por query direta. */
    public function getEnderecoAtualAttribute(): ?PessoaEndereco
    {
        if (array_key_exists('endereco_atual', $this->__pessoa_memo)) {
            return $this->__pessoa_memo['endereco_atual'];
        }

        if ($this->relationLoaded('endereco') && $this->endereco) {
            return $this->__pessoa_memo['endereco_atual'] = $this->endereco;
        }

        $conn = (new PessoaEndereco)->getConnectionName();

        $end = PessoaEndereco::on($conn)
            ->select(['id', 'pessoa_id', 'endereco', 'numero', 'complemento', 'bairro_id', 'cidade_id', 'uf_id', 'cep'])
            ->where('pessoa_id', $this->getKey())
            ->orderByDesc('id')
            ->first();

        return $this->__pessoa_memo['endereco_atual'] = $end ?: null;
    }

    /** Linha única do endereço (tenta accessor de PessoaEndereco; senão monta) */
    public function getEnderecoTextoAttribute(): ?string
    {
        if (array_key_exists('endereco_texto', $this->__pessoa_memo)) {
            return $this->__pessoa_memo['endereco_texto'];
        }

        $end = $this->endereco_atual;
        if (!$end) return $this->__pessoa_memo['endereco_texto'] = null;

        $end->loadMissing([
            'bairro:id,nome',
            'cidade:id,nome',
            'estado:id,sigla',
        ]);

        if (method_exists($end, 'getLinhaCompletaAttribute')) {
            $linha = $end->linha_completa;
            if (!blank($linha)) {
                return $this->__pessoa_memo['endereco_texto'] = $linha;
            }
        }

        $partes = array_filter([
            $end->endereco,
            $end->numero ? 'Nº ' . $end->numero : null,
            $end->complemento,
            optional($end->bairro)->nome,
            optional($end->cidade)->nome,
            optional($end->estado)->sigla,
            $this->formatCep($end->cep),
        ]);

        return $this->__pessoa_memo['endereco_texto'] = ($partes ? implode(', ', $partes) : null);
    }

    /** CEP 00000-000 */
    protected function formatCep(?string $cep): ?string
    {
        if (!$cep) return null;
        $d = preg_replace('/\D+/', '', $cep);
        if (strlen($d) !== 8) return $cep;
        return substr($d, 0, 5) . '-' . substr($d, 5);
    }

    /* =========================
     |  FOTO (data URI leve)
     |=========================*/

    public function getFotoBase64Attribute(): ?string
    {
        $id = $this->getKey();
        if (blank($id)) return null;

        // --- tente reaproveitar eager loading, se disponível ---
        if ($this->relationLoaded('fotos')) {
            /** @var \Illuminate\Support\Collection $fotos */
            $fotos = $this->getRelation('fotos');

            $foto = PessoaFoto::query()
                ->where('pessoa_id', $id)
                ->whereNotNull('img')->whereNotNull('img_type')
                ->select('img', 'img_type')
                ->orderByRaw("
                    CASE
                    WHEN foto_tipo_id = 1 AND foto_posicao_id = 1 THEN 0
                    WHEN foto_tipo_id = 1 THEN 1
                    WHEN foto_posicao_id = 1 THEN 2
                    ELSE 3
                    END
                ")
                ->orderByDesc('id')
                ->first();

            if ($foto && !blank($foto->img) && !blank($foto->img_type)) {
                return "data:{$foto->img_type};base64,{$foto->img}";
            }

            return $this->fotoPlaceholderBase64(); // sem foto válida
        }

        // --- versão com cache (1h). ajuste o ttl conforme quiser ---
        $cacheKey = "pessoa:{$id}:foto_b64_v1";
        return Cache::remember($cacheKey, now()->addHour(), function () use ($id) {
            // 1) escolher a melhor foto usando ORDER BY com score
            $foto = \App\Models\Custodiado\PessoaFoto::query()
                ->where('pessoa_id', $id)
                ->whereNotNull('img')->whereNotNull('img_type')
                ->select('img', 'img_type')
                ->orderByRaw("
                CASE
                  WHEN foto_tipo_id = 1 AND foto_posicao_id = 1 THEN 0
                  WHEN foto_posicao_id = 1 THEN 1
                  ELSE 2
                END
            ")
                ->orderByDesc('id')
                ->first();

            if (!$foto) {
                return $this->fotoPlaceholderBase64();
            }

            return "data:{$foto->img_type};base64,{$foto->img}";
        });
    }

    /**
     * placeholder em base64 uma única vez e guarda em cache para sempre.
     */
    protected function fotoPlaceholderBase64(): ?string
    {
        try {
            return Cache::rememberForever('img:no_image_b64_v1', function () {
                $path = public_path('assets/images/icons/no_image.png');
                if (!is_file($path)) return null;

                $bin = @file_get_contents($path);
                if ($bin === false) return null;

                return 'data:image/png;base64,' . base64_encode($bin);
            });
        } catch (\Throwable $e) {
            return null;
        }
    }


    /* =========================
     |  atalhos de leitura (opcional)
     |=========================*/
    public function enderecoAtual()
    {
        return $this->endereco_atual;
    }
    public function enderecoTexto()
    {
        return $this->endereco_texto;
    }
    public function cpf()
    {
        return $this->cpf;
    }
    public function rg()
    {
        return $this->rg;
    }
}
