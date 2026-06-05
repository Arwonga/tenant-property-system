<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Unit;

class UnitController extends Controller
{
    public function index(Property $property)
    {
        $units = Unit::where('property_id', $property->id)->latest()->get();
        return view('admin.units', compact('property', 'units'));
    }

    public function store(Request $request, Property $property)
    {
        $request->validate([
            'unit_number' => 'required|string|max:255',
            'unit_type' => 'required|string|max:255',
            'rent_amount' => 'required|numeric|min:0',
            'fixed_deposit' => 'required|numeric|min:0', // Validating the deposit
        ]);

        Unit::create([
            'property_id' => $property->id,
            'unit_number' => $request->unit_number,
            'unit_type' => $request->unit_type,
            'rent_amount' => $request->rent_amount,
            'fixed_deposit' => $request->fixed_deposit, // Saving to the database
            'status' => 'vacant', 
        ]);

        return back()->with('success', 'New unit added to ' . $property->name . '!');
    }
}