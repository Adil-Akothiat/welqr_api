<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Utilities;
use App\Models\DefaultMenu;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TestController extends Controller
{
    public function test (Request $request) {
        $menu = DefaultMenu::with('defaultDishes')->get()[0];
        $dish = $menu->defaultDishes[0];
        $extension = explode('.', $dish->image)[1];
        $image = "assets/".$dish->image;
        $sourcePath = public_path($image);
        $imageName = Str::uuid().'.'.$extension;
        $copied = false;
        if(file_exists($sourcePath)) {
            $destinationDir = public_path('assets/data');
            if(!file_exists($destinationDir)) {
                File::makeDirectory($destinationDir, 0755, true);
            }
            $destination = "$destinationDir/$imageName";
            $copy = File::copy($sourcePath, $destination);
            return response()->json(['data'=> explode('/', $destination)]);
        }
        return response()->json(['data'=> $sourcePath]);
    }
}
