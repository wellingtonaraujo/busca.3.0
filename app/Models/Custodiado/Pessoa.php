<?php

namespace App\Models\Custodiado;

use App\Classes\Datas;
use App\Models\Custodiado\PessoaFoto;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Pessoa extends Model
{
    use HasFactory;
    protected $connection = 'siapenweb_dp';
    protected $table      = 'pessoas';
    /*
    	campos da tabela
     */
    protected $fillable   = [
        //dados pessoais
        'nome',
        'homonimo',
        'nome_social',
        'usar_social',
        'alcunha',
        'nascimento',
        'pais_id',
        'estado_id',
        'natural_id',
        'sexo_id',
        'mae',
        'pai',
        //dados gerais
        'cutis_id',
        'cor_olhos_id',
        'altura',
        'cor_cabelo_id',
        'religiao_id',
        'escolaridade_id',
        'estado_civil_id',
        'pne_id',
        'profissao_id',
        'tribo_indigena_id',
        'orientacao_sexual_id',
        'identidade_generos_id',
        //identidade fotografica
        'img',
        'img_type',
        'img_size',
        'principal_pessoa_foto_id',
        //id do banco antigo
        'idpessoa'
    ];

    public function contatos()
    {
        return $this->hasMany(PessoaContato::class, 'pessoa_id', 'id');
    }

    public function documentos()
    {
        return $this->hasMany(PessoaDocumento::class, 'pessoa_id', 'id');
    }

    public function documentosSlim()
    {
        return $this->hasMany(PessoaDocumento::class, 'pessoa_id')
            ->select([
                'id',
                'pessoa_id',
                'documento_tipo_id',
                'expedicao_estado_id',
                'expedicao_pais_id',
                'documento_numero',
                'data_expedicao',
                'orgao_expedicao',
                'descricao',
            ]);
    }

    public function custodiado()
    {
        return $this->hasOne(Custodiado::class, 'pessoa_id', 'id');
    }

    public function vinculado()
    {
        return $this->hasOne(Vinculado::class, 'vinculado_pessoa_id', 'id');
    }

    public function vinculadoCustodiado()
    {
        // return $this->hasMany(VinculadoCustodiado::class, 'vinculado_pessoa_id', 'id');
    }

    public function pessoaCondutaDisciplinar()
    {
        // return $this->hasMany(PessoaCondutaDisciplinar::class, 'pessoa_id', 'id')
        // ->with('condutaDisciplinarTipo', 'condutaDisciplinarTipoPenal', 'condutaDisciplinarStatus', 'resultado')
        // ->orderBy('id', 'desc');
    }

    /**
     * CPF formatado (xxx.xxx.xxx-yy) a partir de PessoaDocumento (tipo=2).
     * Permite usar $pessoa->cpf na Blade.
     */
    public function getCpfAttribute(): ?string
    {
        $numero = PessoaDocumento::query()
            ->where('pessoa_id', $this->id)
            ->where('documento_tipo_id', 2)   // 2 = CPF
            ->latest('id')
            ->value('documento_numero');

        if (blank($numero)) {
            return null;
        }

        // Mantém só dígitos
        $digits = preg_replace('/\D/', '', (string) $numero);

        // Se não tiver 11 dígitos, devolve o valor original (ou retorne null, se preferir)
        if (strlen($digits) !== 11) {
            return $numero;
        }

        // Formata: 000.000.000-00
        return substr($digits, 0, 3) . '.'
            . substr($digits, 3, 3) . '.'
            . substr($digits, 6, 3) . '-'
            . substr($digits, 9, 2);
    }

    /**
     * RG formatado (00.000.000-0) quando houver exatamente 9 dígitos.
     * Permite usar $pessoa->rg na Blade.
     */
    public function getRgAttribute(): ?string
    {
        $doc = PessoaDocumento::query()
            ->where('pessoa_id', $this->id)
            ->where('documento_tipo_id', 1) // 1 = RG
            ->latest('id')
            ->first(['documento_numero', 'expedicao_estado_id']);

        if (!$doc) {
            return null;
        }

        // Normaliza o número (remove pontuação/espacos); mantém letras se existirem
        $numero = preg_replace('/[^0-9A-Za-z]/', '', (string) $doc->documento_numero);
        if ($numero === '') {
            return null;
        }

        // Usa o accessor do próprio model PessoaDocumento
        $uf = $doc->expedicao_estado; // vem de getExpedicaoEstadoAttribute()

        return $uf ? "{$numero}-{$uf}" : $numero;
    }

    // Se quiser continuar tendo um Carbon internamente em outros pontos
    protected $casts = [
        'nascimento' => 'date', // vira Carbon ao ler do banco
    ];

    protected function nascimento(): Attribute
    {
        return Attribute::make(
            // GET (posicional)
            fn($value) =>
            blank($value) ? null : ($value instanceof Carbon
                ? $value->format('d/m/Y')
                : (Carbon::hasFormat($value, 'Y-m-d')
                    ? Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y')
                    : Carbon::parse($value)->format('d/m/Y'))),

            // SET (posicional)
            function ($value) {
                if (blank($value)) return null;

                if ($value instanceof Carbon) {
                    return $value->format('Y-m-d');
                }

                if (Carbon::hasFormat($value, 'd/m/Y')) {
                    return Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
                }

                return Carbon::parse($value)->format('Y-m-d');
            }
        );
    }

    public function getIdadeAttribute(): ?string
    {
        $nascAttr = $this->getAttribute('nascimento');
        if (blank($nascAttr)) {
            return null;
        }

        try {
            // Se já for Carbon (ex.: via $casts = ['nascimento' => 'date'])
            if ($nascAttr instanceof Carbon) {
                $anos = $nascAttr->age;
            } else {
                // Detecta formatos comuns
                if (Carbon::hasFormat($nascAttr, 'Y-m-d')) {
                    $dt = Carbon::createFromFormat('Y-m-d', $nascAttr);
                } elseif (Carbon::hasFormat($nascAttr, 'd/m/Y')) {
                    $dt = Carbon::createFromFormat('d/m/Y', $nascAttr);
                } else {
                    $dt = Carbon::parse($nascAttr);
                }
                $anos = $dt->age;
            }

            return $anos . ' ' . ($anos === 1 ? 'ano' : 'anos');
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function fotos(): HasMany
    {
        return $this->hasMany(\App\Models\Custodiado\PessoaFoto::class, 'pessoa_id');
    }

    /** Data URI derivado da foto principal */
    public function getFotoDataUriAttribute(): string
    {
        // Tenta usar a coleção já carregada (evita N+1)
        $fotos = $this->relationLoaded('fotos')
            ? $this->fotoss
            : \App\Models\Custodiado\PessoaFoto::where('pessoa_id', $this->id)->get();

        $fotoPerfil = $fotos
            ->firstWhere('foto_tipo_id', 1)
            ?? $fotos->firstWhere('foto_posicao_id', 1)
            ?? $fotos->sortByDesc('id')->first();

        if ($fotoPerfil) {
            return "data:{$fotoPerfil->img_type};base64,{$fotoPerfil->img}";
        }

        // fallback
        return \Intervention\Image\Laravel\Facades\Image::read(
            public_path('assets/images/icons/no_image.png')
        )->toPng()->toDataUri();
    }

    public function fotoPrincipal()
    {
        return $this->belongsTo(PessoaFoto::class, 'principal_pessoa_foto_id');
    }

    public function endereco(){
        // return PessoaEndereco::where('pessoa_id', $this->id)->lasted('id');
        return $this->hasOne(PessoaEndereco::class, 'pessoa_id', 'id')->ofMany('id', 'max');
    }
}
