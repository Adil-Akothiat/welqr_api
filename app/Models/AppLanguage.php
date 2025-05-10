<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

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
            $folder = public_path(dirname($child->jsonPath));            
            if (File::exists($folder)) {
                File::deleteDirectory($folder);
            }
        });
    }
}
