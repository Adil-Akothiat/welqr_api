<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasOne, HasMany, BelongsToMany};
use App\Models\{User, Language, Menu, Review, Qrcode, Address, OpeningTimes, SocialNteworks, Wifi, RestaurantCovers };
use Illuminate\Support\Facades\Log;

class Restaurant extends Model
{
    protected $table = "restaurant";
    protected $casts = [
        'isActive'=> 'boolean',
        'visible'=>'boolean'
    ];
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
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'restaurant_user', 'member_id', 'restaurant_id');
    }
    
    protected static function booted() {
        static::deleting(function ($child) {
            // delete qrcode
            $qrcode = Qrcode::find($child->qrcode_id);
            $qrcode->delete();
            if($child->coverImage) {
                $covers = RestaurantCovers::all();
                $exists = false;
                foreach($covers as $cover):
                    if($cover->path === $child->coverImage):
                        $exists = true;
                        break;
                    endif;
                endforeach;
                if($exists === false) {
                    $filePath = public_path('assets/'.$child->coverImage);
                    if(file_exists($filePath)):
                        unlink($filePath);
                    endif;
                }
            }
        });
    }
}
