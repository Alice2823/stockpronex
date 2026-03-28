<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Stock;
use App\Models\StockUsage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_analytics_page_is_accessible()
    {
        $user = User::factory()->create(['plan' => 'pro']);

        $response = $this->actingAs($user)->get(route('dashboard.analytics'));

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.analytics');
    }

    public function test_analytics_data_endpoint_returns_correct_structure()
    {
        $user = User::factory()->create(['plan' => 'pro']);
        
        // Create some data
        $stock = Stock::create([
            'user_id' => $user->id,
            'name' => 'Test Product',
            'quantity' => 100,
            'price' => 10.00
        ]);

        StockUsage::create([
            'user_id' => $user->id,
            'stock_id' => $stock->id,
            'quantity' => 10,
            'notes' => 'Test Usage'
        ]);

        $response = $this->actingAs($user)->get(route('dashboard.analytics.data'));

        $response->assertStatus(200);
        
        $response->assertJsonStructure([
            'cards' => [
                'total_stock',
                'total_used',
                'total_added',
                'low_stock',
            ],
            'charts' => [
                'usage_vs_added' => [
                    'labels',
                    'added',
                    'used',
                ],
                'daily_usage' => [
                    'labels',
                    'data',
                ],
                'stock_trend' => [
                    'labels',
                    'data',
                ],
                'top_products' => [
                    'labels',
                    'data',
                ]
            ]
        ]);
    }
}
