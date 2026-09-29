<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BellscaleCycle extends Model
{
    protected $guarded = [];

     public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'last_completed_at' => 'datetime',
        'next_due_at'       => 'datetime',
    ];
}
