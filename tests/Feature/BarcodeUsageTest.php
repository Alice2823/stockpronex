<?php

namespace Tests\Feature;

use App\Models\Stock;
use App\Models\StockBarcode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BarcodeUsageTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_use_barcode_with_whitespace()
    {
        $user = User::factory()->create(['business_type' => 'Electronics', 'plan' => 'pro']);
        $this->actingAs($user);

        $stock = Stock::create([
            'user_id' => $user->id,
            'name' => 'Test Product',
            'quantity' => 10,
            'price' => 100,
        ]);

        $barcodeValue = 'STK-1234';
        StockBarcode::create([
            'user_id' => $user->id,
            'stock_id' => $stock->id,
            'barcode' => $barcodeValue,
            'status' => 'available',
        ]);

        $response = $this->postJson(route('usage.barcode.use'), [
            'barcode' => '  ' . $barcodeValue . '  ', // With whitespace
            'customer_name' => 'John Doe',
            'phone' => '1234567890',
            'address' => 'Test Address',
            'amount' => 100,
        ]);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);

        $this->assertEquals(9, $stock->fresh()->quantity);
        $this->assertEquals('used', StockBarcode::where('barcode', $barcodeValue)->first()->status);
    }

    public function test_get_details_with_whitespace()
    {
        $user = User::factory()->create(['plan' => 'pro']);
        $this->actingAs($user);

        $stock = Stock::create([
            'user_id' => $user->id,
            'name' => 'Test Product',
            'quantity' => 10,
            'price' => 100,
        ]);

        $barcodeValue = 'STK-5678';
        StockBarcode::create([
            'user_id' => $user->id,
            'stock_id' => $stock->id,
            'barcode' => $barcodeValue,
            'status' => 'available',
        ]);

        $response = $this->getJson(route('barcode.details', ['barcode' => '  ' . $barcodeValue . '  ']));

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => ['name' => 'Test Product']
            ]);
    }
}
