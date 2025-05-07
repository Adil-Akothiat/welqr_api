<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppLanguage extends Model
{
    protected $table = "app_languages";
    protected static function booted() {
        static::deleting(function ($child) {
            if($child->icon) {
                $filePath = public_path('assets/'.$child->icon);
                if(file_exists($filePath)):
                    unlink($filePath);
                endif;
            }
        });
    }
}
