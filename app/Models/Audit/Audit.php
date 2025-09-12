<?php

namespace App\Models\Audit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Audit extends Model
{
    use HasFactory;

    protected $table = 'audits';

    protected $fillable = [
        'user_id',
        'action',
        'table_name',
        'record_id',
        'old_data',
        'new_data',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
