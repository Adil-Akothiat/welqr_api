<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefaultMenu extends Model
{
     protected $fillable = [
        'name',
        'visibility',
        'availibility',
        'filePath',
        'order',
    ];
    public function defaultDishes()
    {
        return $this->hasMany(DefaultDish::class, 'default_menu_id');
    }
}
