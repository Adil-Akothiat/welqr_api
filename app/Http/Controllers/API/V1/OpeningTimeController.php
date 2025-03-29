<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OpeningTimes;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Helpers\Utilities;

class OpeningTimeController extends Controller
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
                'from'=> 'required',
                'to'=> 'required',
                'openStatus'=> 'nullable',
                'restaurant_id'=> 'required'
            ]);
            $openingTime = new OpeningTimes;
            $openingTime->from = $request->from ?? "08:00";
            $openingTime->to = $request->to ?? "18:00";
            $openingTime->openStatus = $request->openStatus ?? 'open';
            $openingTime->restaurant_id = $request->restaurant_id;
            $openingTime->save();
            return Response()->json(['opening_time'=> $openingTime, 'success'=>true])->header('Content-Type', 'application/json');
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
                'from'=> 'nullable',
                'to'=> 'nullable',
                'openStatus'=> 'nullable'
            ]);
            $openingTime = OpeningTimes::find($id);
            if(!$openingTime) {
                throw new NotFoundHttpException("Opening Time not found");
            }
            $openingTime->from = $request->from;
            $openingTime->to = $request->to;
            $openingTime->openStatus = $request->openStatus;
            $openingTime->save();
            return Response()->json(['opening_time'=> $openingTime, 'success'=>true])->header('Content-Type', 'application/json');
        } catch(Exception $e) {
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
