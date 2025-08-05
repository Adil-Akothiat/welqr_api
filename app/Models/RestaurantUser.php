<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantUser extends Model
{
    protected $table = "restaurant_user";
    protected $fillable = [
        'owner_email',
        'restaurant_id',
        'member_id',
        'role',
        'permission'
    ];
}
