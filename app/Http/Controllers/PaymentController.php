<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionPaymentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    protected $paymongo;

    public function __construct(PaymentService $paymongo)
    {
        $this->paymongo = $paymongo;
    }

    public function createIntent(SubscriptionPaymentRequest $request)
    {
        try {
            $data = $request->validated();

            $intent = $this->paymongo->createPaymentIntent($data['amount'], $data['type']);

            return response()->json($intent);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function attachPayment(Request $request)
    {
        try {
            $data = $request->validated();

            $response = $this->paymongo->attachPaymentMethod($data['intent_id'], $data['method_id']);

            if (isset($request['data']['attributes']['status']) && $response['data']['attributes']['status'] === 'succeeded') {
                $user = Auth::user();
                $user->subscription = true;
                $user->save();
            }

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
