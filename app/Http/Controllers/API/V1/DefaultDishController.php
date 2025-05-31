<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DefaultDish;
use App\Helpers\Utilities;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Exception;

class DefaultDishController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $dishes = DefaultDish::all();
            return response()->json(['dishes' => $dishes], 200);
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'file' => 'nullable|file|image|max:5520',
                'name' => 'required',
                'default_menu_id'=> 'required'
            ]);

            if ($request->file) {
                $path = $request->file('file')->store('default_dish', 'public');
            } else {
                $path = $request->path;
            }

            if (!$request->price && !$request->prices) {
                return response()->json(['message' => 'Please set an initial price for the dish'], 422);
            }

            $dish = new DefaultDish;
            $dish->name = $request->name;
            $dish->description = $request->description ?? "";
            $dish->image = $path;
            $dish->price = $request->price;
            $dish->prices = $request->prices;
            $dish->allergens = $request->allergens;
            $dish->tags = $request->tags;
            $dish->default_menu_id = $request->default_menu_id;

            $dish->save();
            $dish->refresh();

            return response()->json(['dish' => $dish], 200);
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
            $dish = DefaultDish::find($id);
            if (!$dish) {
                throw new NotFoundHttpException("Default dish not found");
            }
            return response()->json(['dish' => $dish], 200);
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
                'file' => 'nullable|file|image|max:5520',
                'name' => 'required',
                'path' => 'nullable',
                'default_menu_id'=> 'required'
            ]);

            $dish = DefaultDish::find($id);
            if (!$dish) {
                throw new NotFoundHttpException("Default dish not found");
            }

            $path = $request->path;
            if ($request->file) {
                $path = $request->file('file')->store('default_dish', 'public');

                if ($dish->image) {
                    $filePath = storage_path('app/public/' . $dish->image);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }

            $dish->name = $request->name;
            $dish->description = $request->description ?? $dish->description;
            $dish->image = $path;
            $dish->price = $request->price;
            $dish->prices = $request->prices;
            $dish->allergens = $request->allergens;
            $dish->tags = $request->tags;
            $dish->visibility = $request->visibility ?? $dish->visibility;
            $dish->default_menu_id = $request->default_menu_id;

            $dish->save();
            $dish->refresh();

            return response()->json(['dish' => $dish], 200);
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
            $dish = DefaultDish::find($id);
            if (!$dish) {
                throw new NotFoundHttpException("Default dish not found");
            }

            if ($dish->image) {
                $filePath = storage_path('app/public/' . $dish->image);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $dish->delete();

            return response()->json(['dish' => $dish], 200);
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    /**
     * Get all dishes by category ID.
     */
    public function getDishesByCategory($id)
    {
        try {
            $dishes = DefaultDish::where('category_id', $id)->get();
            return response()->json(['dishes' => $dishes], 200);
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }
}