<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\MaintenanceTicket;
use App\Models\Unit; // Crucial import for checking rooms
use Illuminate\Support\Facades\Auth; // Crucial import for user sessions



class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Calculate total outstanding arrears
        $outstandingBalance = Invoice::where('user_id', $user->id)
            ->whereIn('status', ['unpaid', 'partial', 'overdue'])
            ->sum('total_due') - Invoice::where('user_id', $user->id)
            ->whereIn('status', ['unpaid', 'partial', 'overdue'])
            ->sum('amount_paid');

        // Get the 5 most recent invoices for the ledger
        $recentInvoices = Invoice::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get active maintenance tickets
        $activeTickets = MaintenanceTicket::where('user_id', $user->id)
            ->where('status', '!=', 'Resolved')
            ->with('media') 
            ->get();

        $unit = Unit::with('property')->where('tenant_id', Auth::id())->first();

        // Fetch the tenant's maintenance tickets
        $tickets = MaintenanceTicket::where('user_id', Auth::id())->latest()->get(); 

        return view('tenant.dashboard', compact('outstandingBalance', 'recentInvoices', 'activeTickets', 'unit', 'tickets'));
    }

    public function storeTicket(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $unit = Unit::where('tenant_id', Auth::id())->first();

        MaintenanceTicket::create([
            'user_id' => Auth::id(), 
            'unit_id' => $unit->id,
            'subject' => $request->subject,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Maintenance ticket submitted successfully. Management has been notified!');
    }

    
    public function processPayment(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|min:10',
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $amountToPay = $request->amount;

        // Find the oldest unpaid or partially paid invoice
        $invoice = Invoice::where('user_id', $user->id)
            ->whereIn('status', ['unpaid', 'partial', 'overdue'])
            ->orderBy('due_date', 'asc')
            ->first();

        if (!$invoice) {
            return back()->with('error', 'You do not have any outstanding invoices to pay.');
        }

        // Simulate M-Pesa API Processing Delay & Transaction Database Locking
        \Illuminate\Support\Facades\DB::transaction(function () use ($invoice, $user, $amountToPay, $request) {
            
            // Record the payment in the ledger
            \App\Models\Payment::create([
                'invoice_id' => $invoice->id,
                'user_id' => $user->id,
                'amount' => $amountToPay,
                'payment_method' => 'M-Pesa',
                'reference_code' => 'MPESA' . strtoupper(uniqid()), // Simulated receipt
            ]);

            // Update the invoice balance
            $invoice->amount_paid += $amountToPay;
            
            if ($invoice->amount_paid >= $invoice->total_due) {
                $invoice->status = 'paid';
            } else {
                $invoice->status = 'partial';
            }
            
            $invoice->save();
        });

        return back()->with('success', 'Payment of KES ' . number_format($amountToPay, 2) . ' received successfully via M-Pesa!');
    }
    
}