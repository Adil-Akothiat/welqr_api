<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasOne, HasMany};
use App\Models\{User, Language, Menu, Review, Qrcode, Address, OpeningTimes, SocialNteworks, Wifi };

class Restaurant extends Model
{
    protected $table = "restaurant";
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function qrcode(): HasOne
    {
        return $this->HasOne(Qrcode::class);
    }
    public function language(): HasMany
    {
        return $this->hasMany(Language::class);
    }
    public function address(): HasMany
    {
        return $this->hasMany(Address::class);
    }
    public function menu(): HasMany
    {
        return $this->hasMany(Menu::class);
    }
    public function review(): HasMany
    {
        return $this->hasMany(Review::class);
    }
    public function openingTimes(): HasMany
    {
        return $this->hasMany(OpeningTimes::class);
    }
    public function socialNetworks(): HasMany
    {
        return $this->hasMany(SocialNetworks::class);
    }
    public function wifi(): HasMany
    {
        return $this->hasMany(Wifi::class);
    }
}
