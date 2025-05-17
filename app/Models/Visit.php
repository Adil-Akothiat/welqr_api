<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Qrcode;

class Visit extends Model
{
    protected $table = "visits";
    protected $fillable = ['url', 'referrer', 'userAgent', 'qrcode_id'];
    public function qrcode(): BelongsTo
    {
        return $this->belongsTo(Qrcode::class);
    }
}
