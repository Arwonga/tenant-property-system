<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Unit;
use App\Models\Invoice;

class TenantController extends Controller
{
    public function getDashboardData(Request $request)
    {
        $user = $request->user(); // Sanctum automatically knows who this is based on the token!

        // 1. Get the tenant's assigned room
        $unit = Unit::with('property')->where('tenant_id', $user->id)->first();

        // 2. Fetch all their invoices
        $invoices = Invoice::where('user_id', $user->id)
                           ->latest('invoice_month')
                           ->get();

        // 3. Calculate their exact missing balance
        $currentBalance = $invoices->whereIn('status', ['unpaid', 'partial'])->sum(function($invoice) {
            return $invoice->total_due - $invoice->amount_paid;
        });

        // 4. Package it all up into a perfect JSON response for Flutter
        return response()->json([
            'status' => 'success',
            'data' => [
                'tenant_name' => $user->name,
                'current_balance' => $currentBalance,
                'active_lease' => $unit ? [
                    'property' => $unit->property->name ?? 'N/A',
                    'unit_number' => $unit->unit_number,
                    'rent_amount' => $unit->rent_amount,
                ] : null,
                'recent_invoices' => $invoices->take(5)->map(function($invoice) {
                    return [
                        'id' => $invoice->id,
                        'month' => \Carbon\Carbon::parse($invoice->invoice_month)->format('F Y'),
                        'amount_due' => $invoice->total_due,
                        'amount_paid' => $invoice->amount_paid,
                        'status' => $invoice->status,
                        'due_date' => $invoice->due_date,
                    ];
                })
            ]
        ], 200);
    }
}