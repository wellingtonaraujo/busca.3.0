<?php

namespace App\Models\Audit;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActionLog extends Model
{
    use HasFactory;
    protected $table = 'user_action_logs';

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
