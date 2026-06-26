<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enterprise extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'logo', 'address', 'phone', 'email', 'website',
        'description', 'representative', 'tax_code', 'founded_year'
    ];

    public function properties()
    {
        return $this->hasMany(Property::class);
    }
}
