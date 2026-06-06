<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'user_id', 'unit_id', 'rent_amount', 'utility_amount', 
        'penalty_fee', 'total_due', 'amount_paid', 'due_date', 
        'status', 'invoice_month'
    ];

    // This builds the relationship to fetch the Tenant's details
    public function tenant()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // (If you don't already have the unit relationship, add this one too!)
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
}
