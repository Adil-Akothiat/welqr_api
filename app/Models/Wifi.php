<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Restaurant;

class Wifi extends Model
{
    protected $table = "wifi";
    function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
}
