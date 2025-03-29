<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Restaurant;

class Address extends Model
{
    function restaurant(): BelongsTo
    {
        $this->belongsTo(Restaurant::class);
    }
}
