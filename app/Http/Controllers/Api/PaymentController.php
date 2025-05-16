<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Http\Client\ConnectionException;

class PaymentController extends Controller
{


    public function initiateCinetpayPayment(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'name'   => 'required|string',
            'phone'  => 'required|string',
            'email'  => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        $site_id   = env('CINETPAY_SITE_ID');
        $api_key   = env('CINETPAY_API_KEY');
        $transaction_id = Str::uuid()->toString();

        $payload = [
            'apikey'                => $api_key,
            'site_id'               => $site_id,
            'transaction_id'        => $transaction_id,
            'amount'                => $request->amount,
            'currency'              => 'XAF',
            'description'           => 'Paiement sur le site',
            'notify_url'            => route('cinetpay.notify'),
            'return_url'            => route('cinetpay.success'),
            'customer_name'         => $request->name,
            'customer_email'        => $request->email,
            'customer_phone_number' => $request->phone,
            'channels'              => 'ALL',
            'metadata'              => 'Commande ' . $transaction_id,
        ];

        try {
            // 2. Appel HTTP sans proxy inutile
            $response = Http::timeout(10)
                            ->post('https://api-checkout.cinetpay.com/v2/payment', $payload);


        } catch (ConnectionException $e) {
            // On renvoie le message exact pour debug
            return response()->json([
                'success' => false,
                'message' => 'Connexion CinetPay impossible : ' . $e->getMessage(),
            ], 502);
        }


        $json = $response->json();
        if (($json['code'] ?? null) === '201' && isset($json['data']['payment_url'])) {
            return response()->json([
                'success'      => true,
                'payment_url'  => $json['data']['payment_url'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $json['message'] ?? 'Erreur API CinetPay inattendue',
            'api_response' => $json,                 // <= on renvoie tout le JSON reçu
            'raw_body'     => $response->body(),
        ], $response->status() ?: 500);
    }


    public function notificatePayment(Request $request)
    {

        $transaction_id = $request->transaction_id;

        try {
            $response = Http::post(env('CINETPAY_NOTIFY_URL'), [
                'apikey'         => env('CINETPAY_API_KEY'),
                'site_id'        => env('CINETPAY_SITE_ID'),
                'transaction_id' => $transaction_id
            ]);

            $data = $response->json();
            // \Log::info('CinetPay Check Response:', $data);

            if (isset($data['data']['status'])) {
                if ($data['data']['status'] === 'ACCEPTED') {
                    // Send success notification
                    // event(new PaymentSuccessful($transaction_id));

                    return response()->json([
                        'success' => true,
                        'message' => 'Payment successful'
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed'
            ]);

        } catch (\Exception $e) {
            // \Log::error('CinetPay Notification Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error processing notification'
            ], 500);
        }
    }

    public function successPayment(Request $request)
    {
        // \Log::info('CinetPay Success Callback:', $request->all());

        try {
            $transaction_id = $request->transaction_id;

            // Verify payment status one more time
            $response = Http::post(env('CINETPAY_RETURN_URL'), [
                'apikey'         => env('CINETPAY_API_KEY'),
                'site_id'        => env('CINETPAY_SITE_ID'),
                'transaction_id' => $transaction_id
            ]);

            $data = $response->json();

            if (isset($data['data']['status']) && $data['data']['status'] === 'ACCEPTED') {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment completed successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed'
            ]);

        } catch (\Exception $e) {
            // \Log::error('CinetPay Success Callback Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error processing success callback'
            ], 500);
        }
    }

}
