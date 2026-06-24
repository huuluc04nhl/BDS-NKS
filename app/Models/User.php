<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name', 'email', 'password', 'phone', 'avatar', 'role', 'status', 'point',
    'firstname', 'lastname', 'intro', 'gender', 'website', 'dob', 'pob', 'hometown',
    'id_number', 'id_date', 'id_place', 'province', 'ward', 'permanent_address'
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function savedProperties()
    {
        return $this->hasMany(SavedProperty::class);
    }

    public function demands()
    {
        return $this->hasMany(Demand::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function emailLogs()
    {
        return $this->hasMany(EmailLog::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
