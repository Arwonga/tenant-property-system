<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'property_id', 'unit_number', 'unit_type', 
        'monthly_rent', 'fixed_deposit', 'status'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
