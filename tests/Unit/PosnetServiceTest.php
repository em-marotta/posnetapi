<?php

namespace Tests\Unit;

use App\Models\Card;
use App\Models\Client;
use App\Services\PosnetService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PosnetServiceTest extends TestCase
{
    use RefreshDatabase;

    protected PosnetService $posnet;

    protected function setUp(): void
    {
        parent::setUp();
        $this->posnet = new PosnetService();
    }

    public function test_fails_if_insufficient_funds()
    {
        $client = Client::factory()->create();
        $card = Card::factory()->create([
            'client_id' => $client->id,
            'available_limit' => 50, // Límite disponible.
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Insufficient credit.');

        // Intentamos pagar más de lo que tiene
        $this->posnet->doPayment($card, 100, 1);
    }

    public function test_successful_payment_with_one_installment()
    {
        $client = Client::factory()->create();
        $card = Card::factory()->create([
            'client_id' => $client->id,
            'available_limit' => 100,
        ]);

        $result = $this->posnet->doPayment($card, 100, 1);

        $this->assertEquals("{$client->first_name} {$client->last_name}", $result['client_name']);
        $this->assertEquals(100, $result['total_amount']);
        $this->assertEquals(100, $result['installment_amount']);
    }

    public function test_payment_with_installments_adds_charge()
    {
        $client = Client::factory()->create();
        $card = Card::factory()->create([
            'client_id' => $client->id,
            'available_limit' => 120,
        ]);

        // Pago de $100 en 3 cuotas = 6% total = $106 alcanza.
        $result = $this->posnet->doPayment($card, 100, 3);

        $this->assertEquals(106.0, round($result['total_amount'], 2));
        $this->assertEquals(35.33, round($result['installment_amount'], 2));
    }
}