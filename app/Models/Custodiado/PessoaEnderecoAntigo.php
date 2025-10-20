<?php

namespace App\Models\Custodiado;

use App\Models\Adm\Bairro;
use App\Models\Adm\Cidade;
use App\Models\Adm\Estado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PessoaEnderecoAntigo extends Model
{
    use HasFactory;

    protected $connection = 'siapen';
    protected $table = 'pessoa_endereco';

    // >>> AQUI ESTÁ A CORREÇÃO
    protected $primaryKey = 'idpessoa_endereco';
    public $incrementing = true;     // ajuste para false se não for auto-incremento
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'idpessoa_endereco',
        'idpessoa',
        'cep',
        'idbairro',
        'logradouro',
        'numero_complemento',
        'referencia',
        'idtipo_endereco',
    ];

    public function getEnderecoAttribute(){
        return strtoupper($this->logradouro);
    }

    public function bairro(){
        return $this->hasOne(Bairro::class, 'idbairro', 'idbairro');
    }

    public function getUfAttributes(){
        $bairro = Bairro::where('idbairro', $this->idbairro)->first();
        $cidade = Cidade::find($bairro->cidade_id)->get('estado_id');
        return Estado::find($cidade->estado_id);
    }

    public function getCidadeAttributes(){
        $bairro = Bairro::where('idbairro', $this->idbairro)->first();
        return CIdade::find($bairro->cidade_id);
    }
}
