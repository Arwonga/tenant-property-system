<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = ['name', 'location'];

    public function units()
    {
        return $this->hasMany(Unit::class);
    }
}
