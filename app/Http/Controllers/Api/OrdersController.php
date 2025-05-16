<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrdersModel;
use App\Models\UsersoneModel;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexOrders()
    {
        return response()->json(OrdersModel::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeOrders(Request $request)
    {
        try {
            $order = new OrdersModel();
            $order->user_id = $request->userId;
            $order->plan_id = $request->planId;
            $order->training_id = $request->trainingId;
            $order->total_price_orders = $request->totalPriceOrders;
            $order->choix_orders = $request->choix;
            $order->save();

            return response()->json(['message' => 'Order created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while creating the order',
                'details' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function showOrders($id)
    {
        try {
            $orders = OrdersModel::where('user_id', $id)->get();

            if ($orders->isEmpty()) {
                return response()->json(['message' => 'No orders found for this user'], 404);
            }

            return response()->json(['order' => $orders], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while retrieving orders',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateOrders(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyOrders(Request $request)
    {
        try {
            $orders = OrdersModel::where('id', $request->id);
            $orders->delete();
            return response()->json(['message' => 'Order drop successfully'], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while retrieving orders',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
