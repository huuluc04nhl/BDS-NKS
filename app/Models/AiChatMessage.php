<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AiChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_email',
        'role',
        'content',
        'context_data',
    ];

    protected $casts = [
        'context_data' => 'array',
    ];
}
