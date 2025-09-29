<?php

namespace App\Models\Custodiado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoTipo extends Model
{
    use HasFactory;
    protected $connection = 'siapenweb';
    /*
    	@description nome da tabela do banco
     */
    protected $table = 'documento_tipos';
    /*
    	campos da tabela
     */
    protected $fillable = [
        'descricao',
        'abreviatura',
        'documento_unico',
        'pessoa_tipo_id'
    ];

    public function setDescricaoAttribute($value)
    {
        $this->attributes['descricao'] = Strings::maiuscula($value);
    }
}
