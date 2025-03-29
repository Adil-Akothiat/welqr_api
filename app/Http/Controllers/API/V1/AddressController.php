<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;
use App\Helpers\Utilities;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Exception;

class AddressController extends Controller
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
                "location"=> "nullable",
                "phone"=> "nullable",
                "restaurant_id"=> "required"
            ]);
            $address = new Address;
            $address->location = $request->location;
            $address->phone = $request->phone;
            $address->restaurant_id = $request->restaurant_id;
            $address->save();
            return Response()->json(["address"=> $address])->header('Content-Type', 'application/json');
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
                "location"=> "nullable",
                "phone"=> "nullable"
            ]);
            $address = Address::find($id);
            if (!$address) {
                throw new NotFoundHttpException("Address not found");
            }
            $address->location = $request->location;
            $address->phone = $request->phone;
            $address->save();
            return Response()->json(["address"=> $address])->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
