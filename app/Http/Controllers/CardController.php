<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Client;
use App\Http\Requests\StoreCardRequest;

class CardController extends Controller
{
    public function store(StoreCardRequest $request)
    {
        // Get the client by DNI
        $client = Client::where('dni', $request->dni)->first();

        // Create the card linked to the client
        $card = $client->cards()->create($request->only([
            'card_type', 'bank_name', 'card_number', 'available_limit'
        ]));

        return response()->json([
            'message' => 'Card registered successfully',
            'card' => $card
        ], 201);
    }
}