<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\Card;
use App\Services\PosnetService;

class PaymentController extends Controller
{
    protected $posnet;

    public function __construct(PosnetService $posnet)
    {
        $this->posnet = $posnet;
    }

    public function store(StorePaymentRequest $request)
    {
        $card = Card::where('card_number', $request->card_number)->first();

        try {
            $ticket = $this->posnet->doPayment($card, $request->amount, $request->installments);

            return response()->json([
                'message' => 'Payment successful',
                'ticket' => $ticket
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Payment failed',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}