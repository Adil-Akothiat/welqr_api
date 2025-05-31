<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefaultDish extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'price',
        'prices',
        'allergens',
        'tags',
        'visibility',
        'default_menu_id',
    ];

    public function defaultMenu()
    {
        return $this->belongsTo(DefaultMenu::class, 'default_menu_id');
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
