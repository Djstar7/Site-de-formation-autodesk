<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use NotchPay\NotchPay;
use NotchPay\Payment;

class WebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $signature = $request->header('X-NotchPay-Signature');
        $payload = $request->getContent();

        if (!NotchPay::verifyWebhook($payload, $signature)) {
            abort(400, 'Signature invalide');
        }

        $event = json_decode($payload);

        // Traite l'événement ici

        return response()->json(['status' => 'success']);
    }

}
