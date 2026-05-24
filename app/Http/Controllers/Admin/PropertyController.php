<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;

class PropertyController extends Controller
{
    // Display the properties page
    public function index()
    {
        // Fetch all properties and count how many units each one has
        $properties = Property::withCount('units')->latest()->get();
        return view('admin.properties', compact('properties'));
    }

    // Save a new property to the database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'type' => 'required|in:Residential,Commercial,Mixed',
        ]);

        Property::create([
            'name' => $request->name,
            'location' => $request->location,
            'type' => $request->type,
        ]);

        return back()->with('success', 'New property added to your portfolio!');
    }
}