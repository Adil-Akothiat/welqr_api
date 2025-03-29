<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Restaurant;

class SocialNetworks extends Model
{
    function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
}
