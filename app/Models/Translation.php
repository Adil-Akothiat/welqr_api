<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $table = "translations";

    protected $fillable = [
        'key',
        'translation',
    ];
}