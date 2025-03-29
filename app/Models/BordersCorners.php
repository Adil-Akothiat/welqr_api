<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Qrcode;

class BordersCorners extends Model
{
    public function qrcode () : BelongsTo
    {
        $this->belongsTo(Qrcode::class);
    }
}
