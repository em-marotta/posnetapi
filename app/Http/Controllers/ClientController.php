<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Models\Client;

class ClientController extends Controller
{
    /**
     * Store a newly created client.
     */
    public function store(StoreClientRequest $request)
    {
        // Crea el cliente con los datos validados del request
        $client = Client::create($request->validated());

        return response()->json([
            'message' => 'Client created successfully.',
            'client' => $client
        ], 201);
    }
}
