<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'unit_number',
        'unit_type',
        'rent_amount',
        'fixed_deposit',
        'status',
        'tenant_id', // The new column is now whitelisted!
    ];

    // This builds the relationship to fetch the Tenant's name
    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }
    
    // NEW: This builds the relationship to fetch the Building's name
    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}