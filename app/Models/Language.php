<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Restaurant;

class Language extends Model
{
    protected $table = "languages";
    function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
}
