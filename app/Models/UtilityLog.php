<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UtilityLog extends Model
{
    protected $fillable = [
        'unit_id', 'utility_type', 'previous_reading', 'current_reading', 
        'consumption', 'rate_per_unit', 'total_cost', 'billing_month'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
