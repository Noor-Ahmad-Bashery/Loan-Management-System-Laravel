<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'lastname',
        'phone',
        'address',
        'email',
        'password',
        'age',
        'user_id',
        'age_reference',
        'national_id_image',
        'profile_image'
    ];

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    public function notebook()
    {
        return $this->hasOne(notebooks::class);
    }
    public function payments()
    {
        return $this->hasMany(monthlypayments::class);
    }

    public function additionalpayment()
    {
        return $this->hasMany(additionalpayments::class);
    }

    public function loan()
    {
        return $this->hasOne(loans::class);
    }
    // Accessor for profile image URL
    public function getProfileImageUrlAttribute()
    {
        return $this->profile_image ? Storage::url($this->profile_image) : asset('"images/profiles/john_doe.jpg"');
        
    }

    // Accessor for national ID image URL
    public function getNationalIdImageUrlAttribute()
    {
        return $this->national_id_image ? Storage::url($this->national_id_image) : asset('images/default_id.png');
    }
}
