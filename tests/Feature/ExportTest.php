<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Stock;
use App\Models\StockUsage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_stock_export_excel_accessible()
    {
        $user = User::factory()->create();
        Stock::create([
            'user_id' => $user->id,
            'name' => 'Test Stock',
            'quantity' => 10,
            'price' => 100,
            'description' => 'Test Description',
        ]);

        $response = $this->actingAs($user)->get(route('stocks.export', 'excel'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_stock_export_pdf_accessible()
    {
        $user = User::factory()->create();
        Stock::create([
            'user_id' => $user->id,
            'name' => 'Test Stock',
            'quantity' => 10,
            'price' => 100,
            'description' => 'Test Description',
        ]);

        $response = $this->actingAs($user)->get(route('stocks.export', 'pdf'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_usage_export_excel_accessible()
    {
        $user = User::factory()->create();
        $stock = Stock::create([
            'user_id' => $user->id,
            'name' => 'Test Stock',
            'quantity' => 10,
            'price' => 100,
        ]);

        StockUsage::create([
            'user_id' => $user->id,
            'stock_id' => $stock->id,
            'quantity' => 5,
            'notes' => 'Test Usage',
        ]);

        $response = $this->actingAs($user)->get(route('usage.export', 'excel'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_usage_export_pdf_accessible()
    {
        $user = User::factory()->create();
        $stock = Stock::create([
            'user_id' => $user->id,
            'name' => 'Test Stock',
            'quantity' => 10,
            'price' => 100,
        ]);

        StockUsage::create([
            'user_id' => $user->id,
            'stock_id' => $stock->id,
            'quantity' => 5,
            'notes' => 'Test Usage',
        ]);

        $response = $this->actingAs($user)->get(route('usage.export', 'pdf'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}
