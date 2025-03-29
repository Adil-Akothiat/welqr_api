<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Qrcode;

class Visit extends Model
{
    public function qrcode(): BelongsTo
    {
        return $this->belongsTo(Qrcode::class);
    }
}
