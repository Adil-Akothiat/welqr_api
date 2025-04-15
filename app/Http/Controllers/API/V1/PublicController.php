<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{ Qrcode, Restaurant };
use App\Helpers\Utilities;

class PublicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $qrcodeName)
    {
        try {
            $qrcode = Qrcode::where('name', $qrcodeName)->first();
            if(!$qrcode) {
                return Response()->json(['message'=> 'Item Not Found'], 404)->header('Content-Type', 'application/json');
            }
            $restaurant = Restaurant::with(['address', 'openingTimes', 'language', 'socialNetworks', 'wifi', 'menu.dishes'])->where("qrcode_id", $qrcode->id)->first();

            return Response()->json(['info'=> $restaurant], 200)->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
