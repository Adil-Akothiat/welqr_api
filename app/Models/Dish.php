<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Menu;

class Dish extends Model
{
    protected $table = "dishes";
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }
    protected static function booted() {
        static::deleting(function ($child) {
            if($child->image) {
                $filePath = public_path('assets/'.$child->image);
                if(file_exists($filePath)):
                    unlink($filePath);
                endif;
            }
        });
    }
}
