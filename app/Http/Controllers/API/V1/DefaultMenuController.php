<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{DefaultMenu, DefaultDish};
use App\Helpers\Utilities;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultMenuController extends Controller
{
    /**
     * Display a listing of all default menus with dishes.
     */
    public function index()
    {
        try {
            $menus = DefaultMenu::with('defaultDishes')->get();
            return response()->json(['menus' => $menus]);
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }
    /**
     * Store a new default menu (with or without a file).
    */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'type'=> 'required|in:simple,pdf',
            ]);

            $lastPosition = DefaultMenu::max('order') ?? 0;

            if ($request->type == "simple") {
                $request->validate([
                    'name'=> 'required'
                ]);

                $menu = new DefaultMenu;
                $menu->name = $request->name;
                $menu->order = $lastPosition + 1;
                $menu->filePath = null;
                $menu->save();
                $menu->refresh();

                return response()->json(['menu' => $menu]);
            }

            // Handle PDF upload
            $request->validate([
                'name'=> 'required',
                'file'=> 'required|array',
                'file.*'=> 'required|mimes:pdf|max:5120',
            ]);

            $files = $request->file('file');
            $paths = [];

            foreach ($files as $key => $value) {
                $path = $value->store('menu/defaults', 'public');
                $paths[$key] = $path;
            }

            $menu = new DefaultMenu;
            $menu->name = $request->name;
            $menu->filePath = json_encode($paths);
            $menu->order = $lastPosition + 1;
            $menu->save();
            $menu->refresh();

            return response()->json(['menu' => $menu]);
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    /**
     * Update a default menu.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'name'=> 'required',
                'visibility'=> 'required|boolean',
                'availibility'=> 'required'
            ]);

            $menu = DefaultMenu::find($id);

            if (!$menu) {
                throw new NotFoundHttpException("Default menu not found");
            }

            $menu->name = $request->name;
            $menu->visibility = $request->visibility;
            $menu->availibility = $request->availibility;
            $menu->save();
            $menu->refresh();

            return response()->json(['menu' => $menu]);
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    /**
     * Delete a default menu and its dishes.
     */
    public function destroy(string $id)
    {
        try {
            $menu = DefaultMenu::find($id);
            if (!$menu) {
                throw new NotFoundHttpException("Default menu not found");
            }

            if ($menu->filePath) {
                $files = json_decode($menu->filePath);
                foreach ($files as $filePath) {
                    $fullPath = public_path('assets/'.$filePath);
                    if (file_exists($fullPath)) {
                        unlink($fullPath);
                    }
                }
            }
            foreach ($menu->defaultDishes as $dish) {
                $dish->delete();
            }
            $menu->defaultDishes()->delete();
            $menu->delete();
            return response()->json(['menu' => $menu]);
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    /**
     * Change the order of default menus.
     */
    // public function orderMenu(Request $request, $curr, $swp)
    // {
    //     try {
    //         $request->validate([
    //             'currentOrder'=> 'required|integer',
    //             'secondOrder'=> 'required|integer'
    //         ]);

    //         $current = DefaultMenu::findOrFail($curr);
    //         $swap = DefaultMenu::findOrFail($swp);

    //         $current->order = $request->currentOrder;
    //         $swap->order = $request->secondOrder;

    //         $current->save();
    //         $current->refresh();
    //         $swap->save();
    //         $swap->refresh();

    //         return response()->json(['current'=> $current, 'second'=> $swap]);
    //     } catch (Exception $e) {
    //         return Utilities::errorsHandler($e);
    //     }
    // }
}