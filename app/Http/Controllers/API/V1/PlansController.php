<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\{ Request, Response };
use App\Helpers\Utilities;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Models\Plan;

class PlansController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $plans = Plan::all();
            return Response()->json(['plans'=> $plans], 200)->header('Content-Type', 'application/json');
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
            $validatedOuput = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'billing_cycles' => 'required|array',
                'stripe_price_id' => 'nullable|string|max:255',
                'stripe_product_id' => 'nullable|string|max:255',
                'is_active' => 'boolean',
                'features' => 'nullable',
            ]);
            $plan = Plan::create($validatedOuput);
            return Response()->json(['plan'=> $plan], 200)->header('Content-Type', 'application/json');
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
            $plan = Plan::find($id);
            if(!$plan) {
                throw new NotFoundHttpException("Plan not found!");
            }
            return Response()->json(['plan'=> $plan],200)->header('Content-Type', 'application/json');
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
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'billing_cycles' => 'required|array',
                'stripe_price_id' => 'nullable|string|max:255',
                'stripe_product_id' => 'nullable|string|max:255',
                'is_active' => 'boolean',
                'features' => 'nullable',
            ]);
            $plan = Plan::find($id);
            if(!$plan) {
                throw new NotFoundHttpException("Plan not found!");
            }
            $plan->update($validated);
            return Response()->json(['plan'=> $plan], 200)->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $plan = Plan::find($id);
            if(!$plan) {
                throw new NotFoundHttpException("Plan not found!");
            }
            $plan->delete();
            return Response()->json(['message'=> 'deleted!'], 200)->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }
}
