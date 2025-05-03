<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Helpers\Utilities;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MenuController extends Controller
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
                'type'=> 'required|in:simple,pdf',
            ]);
            $lastPosition = Menu::max('order') ?? 0;
            if($request->type == "simple") {
                $request->validate([
                    'name'=> 'required',
                    'restaurant_id'=> 'required'
                ]);
                $menu = new Menu;
                $menu->name = $request->name;
                $menu->order = $lastPosition + 1;
                $menu->filePath = null;
                $menu->restaurant_id = $request->restaurant_id;
                $menu->save();
                $menu->refresh();
                return Response()->json(['menu'=> $menu])->header('Content-Type', 'application/json');
            }
            $request->validate([
                'name'=> 'required',
                'file'=> 'required|array',
                'file.*'=> 'required|mimes:pdf|max:5120',
                'restaurant_id'=> 'required'
            ]);
            $files = $request->file('file');
            $paths=[];
            foreach($files as $key=> $value):
                $path = $value->store('menu/pdfs', 'public');
                $paths[$key] = $path;
            endforeach;
            $menu = new Menu;
            $menu->name = $request->name;
            $menu->filePath = json_encode($paths);
            $menu->order = $lastPosition + 1;
            $menu->restaurant_id = $request->restaurant_id;
            $menu->save();
            $menu->refresh();
            return Response()->json(['menu'=>$menu], 200)->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'name'=>'required',
                'visibility'=>'required',
                'availibility'=>'required'
            ]);
            $menu = Menu::find($id);
            if(!$menu) {
                throw new NotFoundHttpException("Menu not found");
            }
            $menu->name = $request->name;
            $menu->visibility = $request->visibility;
            $menu->availibility = $request->availibility;
            $menu->save();
            $menu->refresh();
            return Response()->json(['menu'=>$menu])->header('Content-Type', 'application/json');
        } catch(Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $menu = Menu::find($id);
        if(!$menu) {
            throw new NotFoundHttpException("Menu not found"); 
        }
        if($menu->filePath) {
            $files = json_decode($menu->filePath);
            foreach($files as $key=>$value) {
                $filePath = public_path('assets/'.$value);
                if(file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }
        foreach ($menu->dishes as $dish) {
            $dish->delete();
        }
        $menu->delete();
        return Response()->json(['menu'=> $menu])->header('Content-Type', 'application/json');
    }

    public function orderMenu(Request $request,$curr, $swp) {
        try {
            $request->validate([
                'currentOrder'=> 'required',
                'secondOrder'=> 'required'
            ]);
            $current = Menu::find($curr);
            $swap = Menu::find($swp);
            $current->order = $request->currentOrder;
            $swap->order = $request->secondOrder;

            $current->save();
            $current->refresh();
            $swap->save();
            $swap->refresh();

            return Response()->json(['current'=> $current, 'second'=> $swap])->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }
}
