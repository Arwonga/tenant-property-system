<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Unit;
use App\Models\User; // Importing the User model

class UnitController extends Controller
{
    public function index(Property $property)
    {
        // Fetch units and bring their attached tenant data along
        $units = Unit::with('tenant')->where('property_id', $property->id)->latest()->get();
        
        // Fetch all users who have the 'tenant' role
        $tenants = User::where('role', 'tenant')->get();
        
        return view('admin.units', compact('property', 'units', 'tenants'));
    }

    public function store(Request $request, Property $property)
    {
        $request->validate([
            'unit_number' => 'required|string|max:255',
            'unit_type' => 'required|string|max:255',
            'rent_amount' => 'required|numeric|min:0',
            'fixed_deposit' => 'required|numeric|min:0',
        ]);

        Unit::create([
            'property_id' => $property->id,
            'unit_number' => $request->unit_number,
            'unit_type' => $request->unit_type,
            'rent_amount' => $request->rent_amount,
            'fixed_deposit' => $request->fixed_deposit,
            'status' => 'vacant', 
        ]);

        return back()->with('success', 'New unit added to ' . $property->name . '!');
    }

    // NEW: The Move-In Logic
    public function assignTenant(Request $request, Property $property, Unit $unit)
    {
        $request->validate([
            'tenant_id' => 'required|exists:users,id',
        ]);

        $unit->update([
            'tenant_id' => $request->tenant_id,
            'status' => 'occupied', // Automatically update room status!
        ]);

        return back()->with('success', 'Tenant successfully moved into ' . $unit->unit_number . '!');
    }
}