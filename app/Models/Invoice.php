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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
