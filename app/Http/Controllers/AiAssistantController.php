<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockUsage;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiAssistantController extends Controller
{
    /**
     * Handle AI chat message
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessage = $request->input('message');
        $user = Auth::user();

        try {
            // Gather user's stock context
            $context = $this->gatherContext($user);

            // Build conversation with system prompt
            $systemPrompt = $this->buildSystemPrompt($user, $context);

            // Call Gemini API
            $aiResponse = $this->callGemini($systemPrompt, $userMessage);

            return response()->json([
                'success' => true,
                'message' => $aiResponse,
            ]);
        } catch (\Exception $e) {
            Log::error('AI Assistant Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Sorry, I\'m having trouble right now. Please try again in a moment.',
            ], 500);
        }
    }

    /**
     * Gather all relevant stock data for the user
     */
    protected function gatherContext($user)
    {
        $userId = $user->id;

        // All stocks
        $stocks = Stock::where('user_id', $userId)->get();

        // Low stock items (quantity < 10)
        $lowStock = $stocks->where('quantity', '<', 10);

        // Out of stock items
        $outOfStock = $stocks->where('quantity', '<=', 0);

        // Recent usage (last 30 days)
        $recentUsage = StockUsage::whereHas('stock', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->where('created_at', '>=', Carbon::now()->subDays(30))
          ->with('stock:id,name')
          ->get();

        // Top used products (last 30 days)
        $topUsed = StockUsage::whereHas('stock', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->where('created_at', '>=', Carbon::now()->subDays(30))
          ->selectRaw('stock_id, SUM(quantity) as total_used')
          ->groupBy('stock_id')
          ->orderByDesc('total_used')
          ->take(5)
          ->with('stock:id,name,price,mrp')
          ->get();

        // Recent invoices (last 30 days)
        $recentInvoices = Invoice::where('user_id', $userId)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->get();

        // Total inventory value
        $totalValue = $stocks->sum(function ($s) {
            return $s->price * $s->quantity;
        });

        // Total revenue (last 30 days)
        $totalRevenue = $recentInvoices->sum('total_amount');

        // Slow-moving stock (no usage in 30 days)
        $usedStockIds = $recentUsage->pluck('stock_id')->unique();
        $slowMoving = $stocks->filter(function ($s) use ($usedStockIds) {
            return !$usedStockIds->contains($s->id) && $s->quantity > 0;
        });

        return [
            'stocks' => $stocks,
            'low_stock' => $lowStock,
            'out_of_stock' => $outOfStock,
            'recent_usage' => $recentUsage,
            'top_used' => $topUsed,
            'recent_invoices' => $recentInvoices,
            'total_value' => $totalValue,
            'total_revenue' => $totalRevenue,
            'slow_moving' => $slowMoving,
            'total_items' => $stocks->count(),
            'total_units' => $stocks->sum('quantity'),
        ];
    }

    /**
     * Build system prompt with user's data context
     */
    protected function buildSystemPrompt($user, $context)
    {
        $currency = $user->currency == 'INR' ? '₹' : ($user->currency == 'GBP' ? '£' : ($user->currency == 'EUR' ? '€' : '$'));

        $prompt = "You are StockProNex AI Assistant — a smart, friendly inventory management assistant. ";
        $prompt .= "You help users understand their stock data, make smart business decisions, and manage inventory efficiently.\n\n";

        $prompt .= "RULES:\n";
        $prompt .= "- Be concise and helpful. Use bullet points and short paragraphs.\n";
        $prompt .= "- Always reference real data from the context below.\n";
        $prompt .= "- Use {$currency} for currency.\n";
        $prompt .= "- If asked something outside inventory/business scope, politely redirect.\n";
        $prompt .= "- Use emojis sparingly for friendliness (📦 📊 ⚠️ ✅ 💡).\n";
        $prompt .= "- Format numbers nicely (e.g., 1,234.56).\n";
        $prompt .= "- When giving advice, be specific to their actual data.\n\n";

        // User info
        $prompt .= "USER INFO:\n";
        $prompt .= "- Name: {$user->name}\n";
        $prompt .= "- Business: " . ($user->business_name ?: 'Not set') . "\n";
        $prompt .= "- Business Type: " . ($user->business_type ?: 'Not set') . "\n";
        $prompt .= "- Currency: {$currency}\n\n";

        // Summary stats
        $prompt .= "INVENTORY SUMMARY:\n";
        $prompt .= "- Total Products: {$context['total_items']}\n";
        $prompt .= "- Total Units in Stock: " . number_format($context['total_units']) . "\n";
        $prompt .= "- Total Inventory Value: {$currency}" . number_format($context['total_value'], 2) . "\n";
        $prompt .= "- Low Stock Items (< 10 units): {$context['low_stock']->count()}\n";
        $prompt .= "- Out of Stock Items: {$context['out_of_stock']->count()}\n";
        $prompt .= "- Revenue (Last 30 Days): {$currency}" . number_format($context['total_revenue'], 2) . "\n";
        $prompt .= "- Slow-Moving Items (No sales in 30 days): {$context['slow_moving']->count()}\n\n";

        // Stock details
        if ($context['stocks']->isNotEmpty()) {
            $prompt .= "ALL STOCK ITEMS:\n";
            foreach ($context['stocks'] as $stock) {
                $value = $stock->price * $stock->quantity;
                $status = $stock->quantity <= 0 ? '❌ OUT OF STOCK' : ($stock->quantity < 10 ? '⚠️ LOW' : '✅ OK');
                $prompt .= "- {$stock->name}: {$stock->quantity} units | MRP: {$currency}" . number_format($stock->mrp, 2) . " | Price: {$currency}" . number_format($stock->price, 2) . " | Value: {$currency}" . number_format($value, 2) . " | Status: {$status}\n";
            }
            $prompt .= "\n";
        }

        // Top used products
        if ($context['top_used']->isNotEmpty()) {
            $prompt .= "TOP SELLING PRODUCTS (Last 30 Days):\n";
            foreach ($context['top_used'] as $item) {
                $name = $item->stock->name ?? 'Unknown';
                $prompt .= "- {$name}: {$item->total_used} units sold\n";
            }
            $prompt .= "\n";
        }

        // Slow-moving items
        if ($context['slow_moving']->isNotEmpty()) {
            $prompt .= "SLOW-MOVING ITEMS (No sales in 30 days, still in stock):\n";
            foreach ($context['slow_moving']->take(10) as $stock) {
                $prompt .= "- {$stock->name}: {$stock->quantity} units sitting idle\n";
            }
            $prompt .= "\n";
        }

        // Low stock items
        if ($context['low_stock']->isNotEmpty()) {
            $prompt .= "LOW STOCK ALERTS:\n";
            foreach ($context['low_stock'] as $stock) {
                $prompt .= "- {$stock->name}: Only {$stock->quantity} units left!\n";
            }
            $prompt .= "\n";
        }

        return $prompt;
    }

    /**
     * Call Google Gemini API with model fallback
     */
    protected function callGemini($systemPrompt, $userMessage)
    {
        $apiKey = config('services.gemini.api_key');

        if (empty($apiKey)) {
            return "⚠️ AI Assistant is not configured yet. Please add your Gemini API key in the .env file (GEMINI_API_KEY=your_key).";
        }

        // Try multiple models in case one has quota issues
        // gemini-flash-latest is confirmed working for this key
        $models = [
            'gemini-flash-latest',
            'gemini-1.5-flash',
            'gemini-2.0-flash-lite',
            'gemini-2.0-flash',
        ];

        $lastError = null;

        foreach ($models as $model) {
            try {
                // Use v1 for 1.5 models and v1beta for 2.0/latest
                $version = (str_contains($model, '1.5')) ? 'v1' : 'v1beta';

                $response = Http::withOptions(['verify' => false])
                    ->timeout(30)
                    ->post(
                        "https://generativelanguage.googleapis.com/{$version}/models/{$model}:generateContent?key={$apiKey}",
                        [
                            'contents' => [
                                [
                                    'role' => 'user',
                                    'parts' => [
                                        ['text' => $systemPrompt . "\n\nUSER QUESTION: " . $userMessage]
                                    ]
                                ]
                            ],
                            'generationConfig' => [
                                'temperature' => 0.7,
                                'topP' => 0.95,
                                'topK' => 40,
                                'maxOutputTokens' => 1024,
                            ],
                            'safetySettings' => [
                                ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_NONE'],
                                ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_NONE'],
                                ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_NONE'],
                                ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_NONE'],
                            ],
                        ]
                    );

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'I couldn\'t generate a response. Please try again.';
                }

                // If rate limited (429), try next model
                if ($response->status() === 429) {
                    Log::warning("Gemini rate limit hit for model: {$model}");
                    $lastError = 'rate_limit';
                    continue;
                }

                // Other errors
                Log::error('Gemini API Error', ['model' => $model, 'status' => $response->status(), 'body' => $response->body()]);
                $lastError = 'api_error';

            } catch (\Exception $e) {
                Log::error("Gemini connection error for model {$model}: " . $e->getMessage());
                $lastError = 'connection';
                continue;
            }
        }

        // All models failed
        if ($lastError === 'rate_limit') {
            return "⏳ The AI service is temporarily rate-limited. Please wait a minute and try again. This happens when the free API quota is exceeded.";
        }

        return "I'm having trouble connecting to the AI service. Please check your API key configuration.";
    }

    /**
     * Get quick suggestions based on user's data
     */
    public function suggestions()
    {
        $user = Auth::user();
        $userId = $user->id;

        $suggestions = [
            '📊 Give me a stock summary',
            '⚠️ Which items are running low?',
            '📈 What are my top selling items?',
        ];

        // Add contextual suggestions
        $lowStockCount = Stock::where('user_id', $userId)->where('quantity', '<', 10)->count();
        if ($lowStockCount > 0) {
            $suggestions[] = "🔄 What should I reorder?";
        }

        $hasInvoices = Invoice::where('user_id', $userId)->exists();
        if ($hasInvoices) {
            $suggestions[] = "💰 How's my revenue this month?";
        }

        $stockCount = Stock::where('user_id', $userId)->count();
        if ($stockCount > 3) {
            $suggestions[] = "💡 Any slow-moving inventory?";
        }

        return response()->json([
            'suggestions' => array_slice($suggestions, 0, 6),
        ]);
    }
}
