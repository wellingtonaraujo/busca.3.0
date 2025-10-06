<?php

namespace App\Models\Custodiado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VinculadoAntigo extends Model
{
    use HasFactory;
    protected $connection = 'siapen';
    protected $table = 'interno_visitante';
    protected $primaryKey = 'id'; // confirme se é mesmo 'id'
    public $timestamps = false;   // se essa tabela não tiver created_at/updated_at

    protected $fillable = [
        'idinterno',
        'idvisitante',
        'iddia_visita',
        'idstatus',
        'obs_autorizacao',
        'obs_cartao',
        'insert_data',
        'update_user',
        'update_data',
        'mac_evento',
    ];

    // 🔹 Relação com o interno (CustodiadoAntigo)
    public function custodiado()
    {
        return $this->belongsTo(
            CustodiadoAntigo::class,
            'idinterno',   // FK em interno_visitante
            'idinterno'    // PK em custodiado_antigos
        );
    }

    // 🔹 Atalho para pessoa (nome do interno)
    public function pessoa()
    {
        return $this->hasOneThrough(
            PessoaAntiga::class,
            CustodiadoAntigo::class,
            'idinterno',  // CustodiadoAntigo.idinterno → VinculadoAntigo.idinterno
            'id',         // PessoaAntiga.id → CustodiadoAntigo.idpessoa
            'idinterno',  // VinculadoAntigo.idinterno
            'idpessoa'    // CustodiadoAntigo.idpessoa
        );
    }

    public function status()
    {
        return $this->hasOne(GeralStatus::class, 'idstatus', 'idstatus');
    }
}
