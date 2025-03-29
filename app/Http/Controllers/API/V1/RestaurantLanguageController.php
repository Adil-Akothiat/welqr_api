<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RestaurantLanguageController extends Controller
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
                'language'=> 'required',
                'restaurant_id'=> 'required'
            ]);
            $language = new Language;
            $language->language = $request->language;
            $language->restaurant_id = $request->restaurant_id;
            $language->save();
            return Response()->json(['created'=>true])->header('Content-Type', 'application/json');
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
            $language = Language::where("restaurant_id", $id)->first();
            if (!$language) {
                throw new NotFoundHttpException("Languages not found");
            }
            return Response()->json(['language'=> $language], 200)->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'language'=> 'required'
        ]);
        $language = Language::find($id);
        if (!$language) {
            throw new NotFoundHttpException("Language not found");
        }
        $language->language = $request->language;
        $language->save();
        return Response()->json(["language"=> $language])->header('Content-Type', 'application/json');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
