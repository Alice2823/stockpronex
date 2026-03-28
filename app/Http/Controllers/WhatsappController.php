<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WhatsappController extends Controller
{
    /** @var WhatsappService */
    protected $whatsappService;

    public function __construct(WhatsappService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Send invoice via WhatsApp
     */
    public function send($id)
    {
        $invoice = Invoice::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['user', 'stock', 'usage'])
            ->first();

        if (!$invoice) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invoice not found.',
            ], 404);
        }

        if (!$invoice->phone || $invoice->phone === 'N/A') {
            return response()->json([
                'status' => 'error',
                'message' => 'No valid phone number for this invoice.',
            ], 422);
        }

        $result = $this->whatsappService->sendInvoiceOnWhatsApp($invoice);

        return response()->json([
            'status' => $result['success'] ? 'success' : 'error',
            'message' => $result['message'],
            'log_id' => $result['log_id'] ?? null,
        ], $result['success'] ? 200 : 422);
    }

    /**
     * Resend failed WhatsApp invoice
     */
    public function resend($id)
    {
        $invoice = Invoice::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['user', 'stock', 'usage'])
            ->first();

        if (!$invoice) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invoice not found.',
            ], 404);
        }

        $result = $this->whatsappService->sendInvoiceOnWhatsApp($invoice);

        return response()->json([
            'status' => $result['success'] ? 'success' : 'error',
            'message' => $result['message'],
            'log_id' => $result['log_id'] ?? null,
        ], $result['success'] ? 200 : 422);
    }
}
