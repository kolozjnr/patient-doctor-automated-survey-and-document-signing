<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqVideouser extends Model
{
    protected $table = 'faq_videousers';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function faq()
    {
        return $this->belongsTo(Faq::class);
    }
}