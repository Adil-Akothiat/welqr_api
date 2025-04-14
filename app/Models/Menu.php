<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Restaurant;
use Dish;

class Menu extends Model
{
    protected $table = "menus";
    protected $fillable = [
        'name',
        'visibility',
        'availibility',
        'restaurant_id'
    ];
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
    public function dish(): HasMany
    {
        return $this->hasMany(Dish::class);
    }
}
