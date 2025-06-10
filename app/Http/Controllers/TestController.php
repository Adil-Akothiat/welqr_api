<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Utilities;
use App\Models\DefaultMenu;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Restaurant;

class TestController extends Controller
{
    public function test (Request $request, $id) {
        // $restaurant = Restaurant::find($id);
        // if(!$restaurant):
        //     return response()->json(['message'=> 'item not found!'], 404);
        // endif;
        // if($restaurant->isActive):
        //     return response()->json(['message'=> 'item is already active!'], 200);
        // endif;
        // $restaurants = Restaurant::where('isActive', 1)->get();
        // foreach($restaurants as $item):
        //     $item->isActive = false;
        //     $item->save();
        // endforeach;
        // $restaurant->isActive = true;
        // $restaurant->save();
        
        // return response()->json(['data'=> $restaurant]);
    }
}
