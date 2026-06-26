<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = [
        'user_id', 'enterprise_id', 'title', 'slug', 'address', 'geolocation', 'rstype', 
        'transaction_type', 'price', 'formated_price', 'total_area', 
        'bed', 'bath', 'floors', 'direction', 'feature_img', 'images', 
        'description', 'is_verified'
    ];

    protected $casts = [
        'images' => 'array',
        'is_verified' => 'boolean',
        'price' => 'double',
        'total_area' => 'double',
        'bed' => 'integer',
        'bath' => 'integer',
        'floors' => 'integer'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function enterprise()
    {
        return $this->belongsTo(Enterprise::class, 'enterprise_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function savedBy()
    {
        return $this->hasMany(SavedProperty::class);
    }
}
