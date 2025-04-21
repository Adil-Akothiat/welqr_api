<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dish;
use App\Helpers\Utilities;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Exception;

class DishController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'file'=> 'nullable|file|image|max:5520',
                'name'=> 'required',
                'menu_id'=> 'required'
            ]);
            if($request->file) {
                $path = $request->file('file')->store('dish', 'public');
            }else {
                $path = $request->path;
            }
            if(!$request->price && !$request->prices) {
                return Response()->json(['message'=> 'Please set an initial price for the dish'], 422)->header('Content-Type', 'application/json');
            }
            $dish = new Dish;
            $dish->name = $request->name;
            $dish->description = $request->description ?? "";
            $dish->image = $path;
            $dish->price = $request->price;
            $dish->prices = $request->prices;
            $dish->allergens = $request->allergens;
            $dish->tags = $request->tags;
            $dish->menu_id = $request->menu_id;
            
            $dish->save();
            $dish->refresh();

            return Response()->json(['dish'=> $dish], 200)->header('Content-Type', 'application/json');    
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $dish = Dish::find($id);
            if($dish) {
                new NotFoundHttpException("Restaurant not found");
            }
            return Response()->json(['dish'=> $dish], 200)->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'file'=> 'nullable|file|image|max:5520',
                'name'=> 'required',
                'menu_id'=> 'required',
                'path'=> 'nullable'
            ]);
            $path = $request->path;
            if($request->file) {
                $path = $request->file('file')->store('dish', 'public');
            }
            $dish = Dish::find($id);
            if(!$dish) {
                throw new NotFoundHttpException("Dish not found");
            }
            if($dish->image && $request->file) {
                $filePath = public_path('assets/'.$dish->image);
                if(file_exists($filePath)):
                    unlink($filePath);
                endif;
            }
            $dish->name = $request->name;
            $dish->description = $request->description;
            $dish->image = $path;
            $dish->price = $request->price;
            $dish->prices = $request->prices;
            $dish->allergens = $request->allergens;
            $dish->tags = $request->tags;
            $dish->visibility = $request->visibility ?? $dish->visibility;
            $dish->menu_id = $request->menu_id;

            $dish->save();
            $dish->refresh();
            
            return Response()->json(['dish'=> $dish], 200)->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $dish = Dish::find($id);
            if(!$dish) {
                throw new NotFoundHttpException("Dish not found");
            }
            $dish->delete();
            return Response()->json(['dish'=> $dish], 200)->header('Content-Type', 'application/json');
        } catch(Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }
    public function getDishesByMenu($id) {
        try {
            $dishes = Dish::where('menu_id', $id)->get();
            return Response()->json(['dishes'=> $dishes], 200)->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }
}
