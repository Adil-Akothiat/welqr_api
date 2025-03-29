<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{HasOne, HasMany, BelongsTo};
use App\Models\{MainStyle, BordersCorners, Logo, Visit, Restaurant};

class Qrcode extends Model
{
    protected $table = "qrcode";
    public function mainStyle () : HasOne
    {
        return $this->hasOne(MainStyle::class);
    }
    public function bordersCorners () : HasOne
    {
        return $this->hasOne(BordersCorners::class);
    }
    public function logo () : HasOne
    {
        return $this->hasOne(Logo::class);
    }
    public function visit () : HasMany
    {
        return $this->hasMany(Visit::class);
    }
    public function restaurant() : BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
}
