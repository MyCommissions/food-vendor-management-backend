<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PaymentService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = base64_encode(env('PAYMONGO_SECRET_KEY'));
    }
    public function createPaymentIntent($amount, $paymentMethodType)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.paymongo.com/v1/payment_intents', [
            'data' => [
                'attributes' => [
                    'amount' => $amount * 100,
                    'payment_method_allowed' => [$paymentMethodType],
                    'payment_method_options' => ['card', 'gcash'],
                    'currency' => 'PHP',
                ],
            ]
        ]);

        return $response->json();
    }

    public function attachPaymentMethod($paymentIntentId, $paymentMethodId)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post(`https://api.paymongo.com/v1/payment_intents/{$paymentIntentId}/attach`, [
            'data' => [
                'attributes' => [
                    'payment_method' => $paymentMethodId,
                ]
            ]
        ]);

        return $response->json();
    }
}