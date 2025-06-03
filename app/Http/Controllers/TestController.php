<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Utilities;
use App\Models\DefaultMenu;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\RestaurantCovers;

class TestController extends Controller
{
    public function test (Request $request) {
       $covers = RestaurantCovers::all();
       $image = "restaurant/JuyHtrMgNavR6IjgDeMd6ZQgHzQjIAAW1CW1hGk.png";
       $exists = false;
        foreach($covers as $cover):
            if($cover->path === $image):
                $exists = true;
                break;
            endif;
        endforeach;
       return response()->json(['data'=> $exists]);
    }
}
