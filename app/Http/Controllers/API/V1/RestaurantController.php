<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Restaurant;
use App\Models\{ RestaurantCovers, DefaultMenu, Menu, Dish };
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
            'file'=> 'nullable|file|image|max:5120',
            'description'=> 'max:255',
            'qrcode_id'=> 'required',
            'path'=> 'nullable',
            'initialize'=> 'nullable|boolean'
        ]);
        $path = null;
        if($request->file) {
            $path = $request->file('file')->store('restaurant', 'public');
        }else {
            $path = $request->path;
        }
        $restaurant = new Restaurant;
        $restaurant->name = $request->name;
        $restaurant->coverImage = $path;
        $restaurant->description = $request->description;
        $restaurant->mode = $request->mode;
        if($request->initialize):
            $restaurant->isActive = true;
        else:
            $restaurant->isActive = false;
        endif;
        $restaurant->qrcode_id = $request->qrcode_id;
        $restaurant->user_id = $request->user()->id;
        $restaurant->save();
        if($request->initialize) {
            $predefinedMenus = DefaultMenu::with('defaultDishes')->get();
            foreach($predefinedMenus as $predefinedMenu):
                $lastPosition = Menu::max('order') ?? 0;
                $menu = new Menu;
                $menu->name = $predefinedMenu->name;
                $menu->order = $lastPosition + 1;
                $menu->filePath = null;
                $menu->restaurant_id = $restaurant->id;
                $menu->save();
                $newImagePath=null;
                foreach($predefinedMenu->defaultDishes as $defaultDish):
                    $dish = new Dish;
                    if ($defaultDish->image) {
                        $path = Utilities::copyFile('assets/'.$defaultDish->image, 'assets/dish');
                        $dish->name = $defaultDish->name;
                        $dish->description = $defaultDish->description;
                        $dish->image = $path;
                        $dish->price = $defaultDish->price;
                        $dish->prices = $defaultDish->prices;
                        $dish->allergens = $defaultDish->allergens;
                        $dish->tags = $defaultDish->tags;
                        $dish->visibility = $defaultDish->visibility;
                        $dish->menu_id = $menu->id;
                        $dish->save();
                    }
                endforeach;
            endforeach;
        }
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
                'file'=> 'nullable|file|image|max:5120',
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
            $restaurant->description = $request->description;
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
                'file'=> 'required|file|image|max:5120'
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
        }catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    public function setActive($id) {
        try {
            $restaurant = Restaurant::find($id);
            if(!$restaurant):
                return Response()->json(['message'=> 'item not found!'], 404)->header('Content-Type', 'application/json');
            endif;
            if($restaurant->isActive):
                return Response()->json(['message'=> 'item is already active!'], 200)->header('Content-Type', 'application/json');
            endif;
            $restaurants = Restaurant::where('isActive', 1)->get();
            foreach($restaurants as $item):
                $item->isActive = false;
                $item->save();
            endforeach;
            $restaurant->isActive = true;
            $restaurant->save();
            $restaurants = Restaurant::all();
            return Response()->json(['restaurants'=> $restaurants])->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }
}
