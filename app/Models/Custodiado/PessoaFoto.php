<?php

namespace App\Models\Custodiado;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Classes\Strings;
use App\Models\Custodiado\Pessoa;
use App\Models\User;

class PessoaFoto extends Model
{
    use SoftDeletes;

    protected $connection = 'siapenweb_foto';
    protected $table      = 'pessoa_fotos';

    protected $fillable = [
        'pessoa_id',
        'foto_localizacao_id',
        'foto_posicao_id',
        'foto_tipo_id',
        'img',
        'descricao',
        'arquivo',
        'img_type',
        'img_size',
        'user_id',
        'created_at'
    ];

    public function Pessoa()
    {
        return $this->hasOne(Pessoa::class, 'id', 'pessoa_id');
    }

    public function fotoLocalizacao()
    {
        return $this->hasOne(FotoLocalizacao::class, 'id', 'foto_localizacao_id');
    }

    public function fotoPosicao()
    {
        return $this->hasOne(FotoPosicao::class, 'id', 'foto_posicao_id');
    }

    public function fotoTipo()
    {
        return $this->hasOne(FotoTipo::class, 'id', 'foto_tipo_id');
    }

    public function setDescricaoAttribute($value)
    {
        $this->attributes['descricao'] = strtoupper($value);
    }

    public function usuario()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    protected $appends = ['data_uri'];

    public function getDataUriAttribute(): string
    {
        if (!empty($this->img) && !empty($this->img_type)) {
            return "data:{$this->img_type};base64,{$this->img}";
        }

        // fallback seguro (sem registro válido ou sem dados de imagem)
        return asset('assets/images/icons/no_image.png');
    }
}
