<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function createPayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'email' => 'required|email',
            'name' => 'required|string',
            'phone' => 'nullable|string',
        ]);

        // Prépare les données pour l'API NotchPay
        $payload = [
            'amount' => $request->amount,
            'currency' => 'XAF',
            'email' => $request->email,
            'name' => $request->name,
            'phone' => $request->phone ?? '',
            'callback' => route('payment.callback'), // route à définir pour recevoir le callback
        ];

        // Appel HTTP vers NotchPay
        $response = Http::withHeaders([
            'Authorization' => env('NOTCHPAY_API_KEY'), // clé dans .env
            'Content-Type' => 'application/json',
        ])->post('https://api.notchpay.co/payment', $payload);

        if ($response->successful()) {
            $data = $response->json();
            // Redirige vers la page d'autorisation de paiement
            return redirect($data['authorization_url']);
        } else {
            return response()->json([
                'message' => 'Erreur lors de la création du paiement',
                'details' => $response->body()
            ], 500);
        }
    }
}
