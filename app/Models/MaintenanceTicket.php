<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceTicket extends Model
{
    protected $fillable = [
        'user_id', 'unit_id', 'category', 'priority', 'description', 'status'
    ];

    public function tenant()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    // The Polymorphic Link: A ticket can have many media files
    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }
}
