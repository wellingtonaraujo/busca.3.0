<?php

namespace App\Models\Custodiado;

use App\Traits\HasFotoSsh;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Intervention\Image\Laravel\Facades\Image;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PessoaAntiga extends Model
{
    use HasFactory;
    // use HasFotoSsh;

    /** Conexão/tabela do banco legado */
    protected $connection = 'siapen';
    protected $table = 'tbpessoa';
    protected $primaryKey = 'id';
    protected $remoteFile = "/home/servidor/gsip/images/";
    public $timestamps = false;

    /** Campos básicos (ajuste conforme seu schema) */
    protected $fillable = [
        'nome',
        'alcunha',
        'nascimento',
        'estado_civil_id',
        'escolaridade_id',
        'profissao_id',
        'religiao_id',
        'altura',
        // se a tabela tiver foto em base64:
        // 'img', 'type',
    ];

    /** Casts úteis */
    protected $casts = [
        'nascimento' => 'date:Y-m-d',
        'altura'     => 'float',
    ];

    /** (Opcional) expõe 'foto' no JSON com o accessor do Trait */
    protected $appends = ['foto']; // já estava aí

    public function getNascimentoAttributes($value){
        return $value ? Carbon::parse($value)->format('d/m/Y') : null;
    }

    /* ---------------- Relações ---------------- */

    public function custodiadoAntigo(): HasOne
    {
        // interno.idpessoa → tbpessoa.id
        return $this->hasOne(CustodiadoAntigo::class, 'idpessoa', 'id');
    }

    public function vinculadosComoVisitante(): HasMany
    {
        return $this->hasMany(VinculadoAntigo::class, 'idvisitante', 'id');
    }

    public function vinculadosComoInterno(): HasManyThrough
    {
        return $this->hasManyThrough(
            VinculadoAntigo::class,   // tabela final
            CustodiadoAntigo::class,  // intermediária
            'idpessoa',               // FK na intermediária → tbpessoa.id
            'idinterno',              // FK na final → custodiado_antigo.idinterno
            'id',                     // PK local
            'idinterno'               // chave usada no vínculo
        );
    }

    public function visitantes(): HasMany
    {
        return $this->hasMany(VinculadoAntigo::class, 'idvisitante', 'id');
    }

    public function documentosAntigos(): HasMany
    {
        return $this->hasMany(PessoaDocumentoAntigo::class, 'idpessoa', 'id');
    }

    public function contatosAntigos(): HasMany
    {
        return $this->hasMany(PessoaContatoAntigo::class, 'idpessoa', 'id');
    }

    public function internoVisitante(): HasOne
    {
        return $this->hasOne(InternoVisitante::class, 'idvisitante', 'id');
    }

    // public function fotoSsh(): ?string
    // {
    //     // Fallback padrão (ajuste o caminho se quiser)
    //     $fallbackPath = public_path('assets/images/icons/no_image.png');

    //     try {
    //         // 1) Se a própria pessoa tiver img base64 na tabela (campos opcionais)
    //         if (!empty($this->img) && !empty($this->type)) {
    //             // retorna data-uri direto do BD
    //             return "data:{$this->type};base64,{$this->img}";
    //         }

    //         // 2) Tenta foto de visitante (legado)
    //         $foto = DB::connection('siapen')
    //             ->table('visitante_foto')
    //             ->where('idvisitante', $this->id)   // 'id' da tbpessoa
    //             ->where('idposicao', 1)
    //             ->first();

    //         if ($foto && !empty($foto->arquivo)) {
    //             // caminho remoto relativo à raiz SFTP configurada (SFTP_ROOT)
    //             $remoteFile = "vinculos/{$foto->arquivo}";

    //             if (Storage::disk('sftp')->fileExists($remoteFile)) {
    //                 $content = Storage::disk('sftp')->get($remoteFile);
    //                 return Image::read($content)->toDataUri();
    //             }
    //         }

    //         // 3) Tenta foto como interno (custodiado)
    //         $interno = DB::connection('siapen')
    //             ->table('interno')
    //             ->where('idpessoa', $this->id)
    //             ->orderBy('idinterno')
    //             ->first();

    //         if ($interno) {
    //             $fotoInterno = DB::connection('siapen')
    //                 ->table('interno_foto')
    //                 ->where('idinterno', $interno->idinterno)
    //                 ->where('idposicao', 1)
    //                 ->orderByDesc('arquivo')
    //                 ->first();

    //             if ($fotoInterno && !empty($fotoInterno->arquivo)) {
    //                 $remoteFile = "/home/servidor/gsip/images/custodiados/{$fotoInterno->arquivo}";

    //                 if (Storage::disk('sftp')->fileExists($remoteFile)) {
    //                     $content = Storage::disk('sftp')->get($remoteFile);
    //                     dd($content);
    //                     return Image::read($content)->toDataUri();
    //                 }

    //                 dd("deu merda");
    //             }
    //         }

    //         // 4) Sem nada no SFTP/BD → fallback local
    //         return Image::read($fallbackPath)->toDataUri();
    //     } catch (\Throwable $e) {
    //         \Log::warning('fotoSsh fallback: ' . $e->getMessage(), ['pessoa_id' => $this->id]);
    //         // Em erro, retorna fallback local
    //         try {
    //             return Image::read($fallbackPath)->toDataUri();
    //         } catch (\Throwable $e2) {
    //             \Log::error('fotoSsh fallback falhou: ' . $e2->getMessage());
    //             return null;
    //         }
    //     }
    // }

    public function getFotoAttribute(): ?string
    {
        return $this->buildFotoDataUri(); // chama um método privado com a lógica
    }

    /** toda a tua lógica atual (SFTP, BD, fallback) aqui */
    private function buildFotoDataUri(): ?string
    {
        // $fallbackPath = public_path('assets/images/icons/no_image.png');

        try {
            // 1) base64 no BD
            if (!empty($this->img) && !empty($this->type)) {
                return "data:{$this->type};base64,{$this->img}";
            }

            // 2) visitante
            $foto = DB::connection('siapen')
                ->table('visitante_foto')
                ->where('idvisitante', $this->id) // PK da tbpessoa
                ->where('idposicao', 1)
                ->first();

            if ($foto && !empty($foto->arquivo)) {
                $remoteFile = "vinculos/{$foto->arquivo}";
                if (Storage::disk('sftp')->fileExists($remoteFile)) {
                    $content = Storage::disk('sftp')->get($remoteFile);
                    return Image::read($content)->toJpeg(85)->toDataUri();
                }
            }

            // 3) interno
            $interno = DB::connection('siapen')
                ->table('interno')
                ->where('idpessoa', $this->id)
                ->orderBy('idinterno')
                ->first();

            if ($interno) {
                $fotoInterno = DB::connection('siapen')
                    ->table('interno_foto')
                    ->where('idinterno', $interno->idinterno)
                    ->where('idposicao', 1)
                    ->orderByDesc('arquivo')
                    ->first();

                if ($fotoInterno && !empty($fotoInterno->arquivo)) {
                    $remoteFile = "custodiados/{$fotoInterno->arquivo}";
                    if (Storage::disk('sftp')->fileExists($remoteFile)) {
                        $content = Storage::disk('sftp')->get($remoteFile);

                        return Image::read($content)->toJpeg(85)->toDataUri();
                    }
                }
            }

            // 4) fallback local
            return Image::read($fallbackPath)->toDataUri();
        } catch (\Throwable $e) {
            \Log::warning('foto fallback: ' . $e->getMessage(), ['pessoa_id' => $this->id]);
            try {
                return Image::read(public_path('assets/images/icons/no_image.png'))
                    ->toPng()
                    ->toDataUri();
            } catch (\Throwable $e2) {
                \Log::error('fallback falhou: ' . $e2->getMessage());
                return null;
            }
        }
    }


    /* -------- Accessors computados -------- */

    public function getVinculadoAntigoAttribute()
    {
        $visitantes = $this->relationLoaded('vinculadosComoVisitante')
            ? $this->getRelation('vinculadosComoVisitante')
            : $this->vinculadosComoVisitante()->get();

        $internos = $this->relationLoaded('vinculadosComoInterno')
            ? $this->getRelation('vinculadosComoInterno')
            : $this->vinculadosComoInterno()->get();

        return $visitantes->concat($internos)->values();
    }

    /** (Opcional) se quiser declarar explicitamente para o Trait */
    // public function getLegacyPessoaIdForFoto(): ?int
    // {
    //     return $this->id; // aqui você pode customizar se necessário
    // }
}
