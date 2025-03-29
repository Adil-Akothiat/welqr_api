<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wifi;
use App\Helpers\Utilities;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WifiController extends Controller
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
                'networkName'=> 'nullable',
                'networkPassword'=> 'nullable',
                'restaurant_id'=> 'required'
            ]);
            $wifi = new Wifi;
            $wifi->networkName = $request->networkName;
            $wifi->networkPassword = $request->networkPassword;
            $wifi->restaurant_id = $request->restaurant_id;
            $wifi->save();
            return Response()->json(['wifi'=> $wifi, 'success'=>true])->header('Content-Type', 'application/json');
        } catch(Exception $e) {
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
                'networkName'=> 'nullable',
                'networkPassword'=> 'nullable'
            ]);
            $wifi = Wifi::find($id);
            if (!$wifi) {
                throw new NotFoundHttpException("Wifi not found");
            }
            $wifi->networkName = $request->networkName;
            $wifi->networkPassword = $request->networkPassword;
            $wifi->save();
            return Response()->json(['wifi'=> $wifi, 'success'=>true])->header('Content-Type', 'application/json');
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
