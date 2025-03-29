<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Utilities;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Models\SocialNetworks;

class SocialNetworksController extends Controller
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
                'platform'=> 'required',
                'link'=> 'nullable',
                'restaurant_id'=> 'required'
            ]);
            $socialNetwork = new SocialNetworks;
            $socialNetwork->platform = $request->platform;
            $socialNetwork->link = $request->link;
            $socialNetwork->restaurant_id = $request->restaurant_id;
            $socialNetwork->save();
            return Response()->json(["social_network"=> $socialNetwork])->header('Content-Type', 'application/json');
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
                'link'=> 'nullable'
            ]);
            $socialNetwork = SocialNetworks::find($id);
            if (!$socialNetwork) {
                throw new NotFoundHttpException("Social Network not found");
            }
            $socialNetwork->link = $request->link;
            $socialNetwork->save();
            return Response()->json(["social_network"=> $socialNetwork])->header('Content-Type', 'application/json');
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
