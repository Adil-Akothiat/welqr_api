<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Restaurant;
use App\Models\RestaurantCovers;
use Exception;
use App\Helpers\Utilities;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $restaurants = Restaurant::all();
            return Response()->json(['restaurants'=> $restaurants], 200)->header('Content-Type', 'application/json');
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
            'name'=> 'required|max:30|unique:restaurant',
            'file'=> 'nullable|file|mimes:jpg,jpeg,png,webp,avif|max:5120',
            'description'=> 'max:255',
            'qrcode_id'=> 'required',
            'path'=> 'nullable'
        ]);
        $path;
        if($request->file) {
            $path = $request->file('file')->store('restaurant', 'public');
        }else {
            $path = $request->path;
        }
        $restaurant = new Restaurant;
        $restaurant->name = $request->name;
        $restaurant->coverImage = $path;
        $restaurant->description = $request->description ?? "";
        $restaurant->mode = $request->mode;
        $restaurant->qrcode_id = $request->qrcode_id;
        $restaurant->user_id = $request->user()->id;
        $restaurant->save();

        return Response()->json(['restaurant'=> $restaurant])->header('Content-Type', 'application/json');
        }catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $restaurant = Restaurant::find($id);
            if(!$restaurant) {
                return Response()->json(['message'=> 'Restaurant not found'], 404)->header('Content-Type', 'application/json');
            }
            return Response()->json(['restaurant'=> $restaurant], 200)->header('Content-Type', 'application/json');
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
                'name'=> 'required|max:30',
                'file'=> 'nullable|file|mimes:jpg,jpeg,png,webp,avif|max:5120',
                'description'=> 'max:255',
                'path'=> 'nullable',
                'qrcode_id'=> 'required'
            ]);
            $path;
            if($request->file) {
                $path = $request->file('file')->store('restaurant', 'public');
            }else {
                $path = $request->path;
            }
            $restaurant = Restaurant::find($id);
            if(!$restaurant) {
                throw new NotFoundHttpException("Restaurant not found");
            }
            $restaurant->name = $request->name;
            $restaurant->coverImage = $path;
            $restaurant->description = $request->description || "";
            $restaurant->mode = $request->mode;
            $restaurant->currency = $request->currency;
            $restaurant->qrcode_id = $request->qrcode_id;
            $restaurant->user_id = $request->user()->id;
            $restaurant->save();
    
            return Response()->json(['restaurant'=> $restaurant])->header('Content-Type', 'application/json');
        }catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $restaurant = Restaurant::find($id);
            if(!$restaurant) {
                return Response()->json(['message'=> 'Restaurant not found'], 404)->header('Content-Type', 'application/json');
            }
            $restaurant->delete();
            return Response()->json(['deleted'=> true], 200)->header('Content-Type', 'application/json');
        } catch(Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }
    public function createCover(Request $request) {
        try {
            $request->validate([
                'file'=> 'required|file|mimes:jpg,jpeg,png,webp,avif|max:5120'
            ]);
            $path = $request->file('file')->store('restaurant', 'public');
            $cover = new RestaurantCovers;
            $cover->path = $path;
            $cover->save();
            return Response()->json(['cover'=> $cover])->header('Content-Type', 'application/json');
        }catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }
    public function getCovers($id) {
        try {
            if($id == 'all') {
                $covers = RestaurantCovers::all();
                return Response()->json(['covers'=> $covers])->header('Content-Type', 'application/json');    
            }
            $cover = RestaurantCovers::find($id);
            return Response()->json(['cover'=> $cover])->header('Content-Type', 'application/json');
        }catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }
    public function deleteCover($id) {
        try {
            $cover = RestaurantCovers::find($id);
            if(!$cover) {
                return Response()->json(['message'=> 'Cover not exists!'])->header('Content-Type', 'application/json');
            }
            $filePath = public_path('assets/'.$cover->path);
            if(file_exists($filePath)) {
                unlink($filePath);
            } 
            $cover->delete();
            return Response()->json(['cover'=> $filePath, 'deleted'=> true])->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }
    public function getRestaurantsByUser($user_id) {
        try {
            $restaurants = Restaurant::with(['language', 'address', 'openingTimes', 'socialNetworks', 'wifi', 'menu'])->where("user_id", $user_id)->get();
            return Response()->json(['restaurants'=> $restaurants])->header('Content-Type', 'application/json');
        }catch (Exception) {
            return Utilities::errorsHandler($e);
        }
    }
}
