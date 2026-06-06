<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Unit;
use App\Models\Invoice;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Calculate Total Revenue from Paid Invoices
        $totalRevenue = Invoice::sum('amount_paid');

        // NEW: Calculate Uncleared Arrears (Total Due minus Amount Paid on unpaid/partial invoices)
        $totalArrears = Invoice::whereIn('status', ['unpaid', 'partial'])->get()->sum(function($invoice) {
            return $invoice->total_due - $invoice->amount_paid;
        });

        // 2. Count Properties and Units
        $totalProperties = Property::count();
        $totalUnits = Unit::count();
        
        // 3. Calculate Occupancy
        $occupiedUnits = Unit::where('status', 'occupied')->count();
        $vacantUnits = Unit::where('status', 'vacant')->count();

        // 4. Fetch the latest 5 paid invoices to show recent cash flow
        $recentPayments = Invoice::with('unit.property', 'tenant')
                                 ->where('amount_paid', '>', 0)
                                 ->latest('updated_at')
                                 ->take(5)
                                 ->get();

        return view('admin.dashboard', compact(
            'totalRevenue', 
            'totalArrears', 
            'totalProperties', 
            'totalUnits', 
            'occupiedUnits', 
            'vacantUnits',
            'recentPayments'
        ));
    }
}