<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentAssignment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'document_id',
        'user_id',
        'token',
        'docuseal_submission_id',
        'signed_doc_url',
        'download_url',
        'docuseal_slug',
        'signing_url',
        'status',
        'signed_at',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
