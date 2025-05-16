<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PlansModel;
use Illuminate\Http\Request;

class PlansController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexPlans()
    {
        return response()->json(PlansModel::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storePlans(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function showPlans($id)
    {
        try {
            $plan = PlansModel::where('id', $id)->first();

            if (!$plan) {
                return response()->json(['message' => 'plan not found'], 404);
            }

            return response()->json(['plan' => $plan], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // erreurs de validation
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatePlans(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyPlans(string $id)
    {
        //
    }
}
