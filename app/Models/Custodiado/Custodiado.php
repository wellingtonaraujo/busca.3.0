<?php

namespace App\Models\Custodiado;

use App\Classes\Datas;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Custodiado extends Model
{
    use HasFactory;
    protected $connection = 'siapenweb_dp';
    protected $table      = 'custodiados';
    protected $fillable = [
        'pessoa_id',
        'regime_id',
        'custodiado_situacao_atual_id',
        'cnc',
        'rji',
        'numero_arquivo',
        'biometria',
        'dna',
        'idinterno',
    ];

    public function pessoa()
    {
        return $this->hasOne(Pessoa::class, 'id', 'pessoa_id');
    }

    public function regime()
    {
        return $this->hasOne(Regime::class, 'id', 'regime_id');
    }

    public function situacaoAtual()
    {
        return $this->hasOne(CustodiadoSituacaoAtual::class, 'id', 'custodiado_situacao_atual_id');
    }

    public function regimeSituacaoAtual()
    {
        try {
            $regime = $this->regime;
            return $regime->descricao;
        } catch (\Error $e) {
            $situacaoAtual = $this->situacaoAtual;
            return $situacaoAtual->descricao;
        } catch (\Throwable $th) {
            return '';
        }
    }

    public function fotoRel(): HasOne
    {
        return $this->hasOne(\App\Models\Custodiado\PessoaFoto::class, 'pessoa_id', 'pessoa_id')
            ->select(['id', 'pessoa_id', 'img', 'img_type', 'foto_tipo_id', 'foto_posicao_id'])
            ->orderByRaw("
            CASE
                WHEN foto_tipo_id = 1 THEN 0
                WHEN foto_posicao_id = 1 THEN 1
                ELSE 2
            END
        ")
            ->orderByDesc('id')
            ->withDefault(function ($foto) {
                // força cair no fallback no accessor se não houver imagem
                $foto->img = null;
                $foto->img_type = null;
            });
    }

    /** Retorna SEMPRE um src exibível (data URI ou asset fallback) */
    public function getFotoAttribute(): string
    {
        // usa a relação se já vier carregada; senão, busca
        $foto = $this->relationLoaded('fotoRel')
            ? $this->getRelation('fotoRel')
            : $this->fotoRel()->first();

        if ($foto && !empty($foto->img) && !empty($foto->img_type)) {
            return "data:{$foto->img_type};base64,{$foto->img}";
        }

        // Se não houver imagem no banco, lê o arquivo padrão e converte para base64
        $path = public_path('assets/images/icons/no_image.png');

        if (file_exists($path)) {
            $base64 = base64_encode(file_get_contents($path));
            $mime = mime_content_type($path);
            return "data:{$mime};base64,{$base64}";
        }

        // fallback final se o arquivo não existir
        return '';
    }
}
