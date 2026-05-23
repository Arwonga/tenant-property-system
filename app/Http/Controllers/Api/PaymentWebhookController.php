<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Validate the incoming payload from the payment gateway
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric',
            'reference' => 'required|string|unique:payments,reference_code',
        ]);

        try {
            // 2. Wrap the entire process in a DB::transaction for absolute financial security
            DB::transaction(function () use ($validated) {
                
                // lockForUpdate() prevents double-spending if two webhooks fire at the exact same millisecond
                $invoice = Invoice::lockForUpdate()->findOrFail($validated['invoice_id']);

                // Record the physical payment receipt
                Payment::create([
                    'invoice_id' => $invoice->id,
                    'user_id' => $invoice->user_id,
                    'amount' => $validated['amount'],
                    'payment_method' => 'Digital API',
                    'reference_code' => $validated['reference'],
                ]);

                // Calculate partial vs full payments
                $invoice->amount_paid += $validated['amount'];
                
                if ($invoice->amount_paid >= $invoice->total_due) {
                    $invoice->status = 'paid';
                } elseif ($invoice->amount_paid > 0) {
                    $invoice->status = 'partial';
                }

                $invoice->save();
            });

            return response()->json(['status' => 'success', 'message' => 'Payment secured and reconciled.'], 200);

        } catch (\Exception $e) {
            // If anything fails, Laravel rolls back the database and logs the error silently
            Log::error('Webhook Transaction Failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Transaction rolled back.'], 500);
        }
    }
}
