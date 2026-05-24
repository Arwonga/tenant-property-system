<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Unit;
use App\Models\User;
use App\Models\Invoice;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_properties' => Property::count(),
            'total_units' => Unit::count(),
            'active_tenants' => User::where('role', 'tenant')->count(),
            'outstanding_revenue' => Invoice::whereIn('status', ['unpaid', 'partial', 'overdue'])->sum('total_due') - Invoice::whereIn('status', ['unpaid', 'partial', 'overdue'])->sum('amount_paid'),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}