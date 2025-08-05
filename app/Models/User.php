<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\{HasOne, HasMany, BelongsToMany};
use Laravel\Sanctum\HasApiTokens;
use App\Models\{Plan, Restaurant, UserSettings};

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'account_confirmation',
        'email_verified_at',
        'photo',
        'plans_id',
        'google_user'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
    public function plan(): HasOne
    {
        return $this->hasOne(Plan::class);
    }
    public function restaurant(): HasMany
    {
        return $this->hasMany(Restaurant::class);
    }
    public function memberRestaurants(): BelongsToMany
    {
        return $this->belongsToMany(Restaurant::class, 'restaurant_user');
    }
    public function settings(): HasOne
    {
        return $this->hasOne(UserSettings::class);
    }
}