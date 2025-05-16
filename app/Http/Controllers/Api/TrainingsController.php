<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrainingsModel;
use Illuminate\Http\Request;

class TrainingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexTrainings()
    {
        return response()->json(TrainingsModel::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeTrainings(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function showTrainings($id)
    {
        try {
            $training = TrainingsModel::where('id', $id)->first();

            if (!$training) {
                return response()->json(['message' => 'training not found'], 404);
            }

            return response()->json(['training' => $training], 201);
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
    public function updateTrainings(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyTrainings(string $id)
    {
        //
    }
}
