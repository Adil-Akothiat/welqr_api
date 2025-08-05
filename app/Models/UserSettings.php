<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo};
use User;

class UserSettings extends Model
{
    protected $table="user_settings";
    protected $fillable = [
        'user_id',
        'active_restaurant'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
