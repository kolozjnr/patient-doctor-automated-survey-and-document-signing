<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    // protected $fillable = [
    //     'title',
    //     'description',
    //     'file_type',
    //     'file_name',
    //     'file_url',
    //     'docuseal_template_id',
    //     'created_by',
    // ];

    public function assignments()
    {
        return $this->hasMany(DocumentAssignment::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'document_assignments', 'document_id', 'user_id')
                    ->withPivot('docuseal_submission_id', 'signing_url', 'status', 'signed_at')
                    ->withTimestamps();
    }

    public function scopeSign($query)
    {
        return $query->where('document_type', 'sign');
    }
    public function scopeSimple($query)
    {
        return $query->where('document_type', 'simple');
    }
}
