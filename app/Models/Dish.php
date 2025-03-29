<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Menu;

class Dish extends Model
{
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }
}
