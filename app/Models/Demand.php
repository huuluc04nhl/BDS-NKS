<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demand extends Model
{
    protected $fillable = [
        'user_id', 'title', 'transaction_type', 'area', 'budget', 'content'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
