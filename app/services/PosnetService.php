<?php

namespace App\Services;

use App\Models\Card;

class PosnetService
{
    /**
     * Process a payment using a credit card.
     */
    public function doPayment(Card $card, float $amount, int $installments): array
    {
        $charge = $installments > 1 ? ($installments - 1) * 0.03 : 0;

        // Calcula el recargo total
        $chargeAmount = $amount * $charge;

        // Suma el recargo al monto original
        $finalAmount = $amount + $chargeAmount;

        if ($card->available_limit < $finalAmount) {
            throw new \Exception("Insufficient credit.");
        }

        $card->available_limit -= $finalAmount;
        $card->save();

        $payment = $card->payments()->create([
            'original_amount' => $amount,
            'final_amount' => $finalAmount,
            'installments' => $installments
        ]);

        return [
            'client_name' => $card->client->first_name . ' ' . $card->client->last_name,
            'total_amount' => round($finalAmount, 2),
            'installment_amount' => round($finalAmount / $installments, 2)
        ];
    }
}