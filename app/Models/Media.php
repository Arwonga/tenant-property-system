<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = ['file_path', 'file_name', 'mime_type'];

    // This allows Media to attach to ANY other model
    public function model()
    {
        return $this->morphTo();
    }
}
