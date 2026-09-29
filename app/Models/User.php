<?php

namespace App\Models;

use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, SoftDeletes, Notifiable, InteractsWithMedia, HasRoles, HasApiTokens;
    protected $guard_name = 'web';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded =[];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    

 
    /**
     * Many-to-many relationship with labels
     */
     public function labels()
    {
        return $this->belongsToMany(
            Label::class, 
            'user_treatment_labels',
            'user_id', 
            'label_id'
        )->withTimestamps();
    }
 public function departments()
    {
        return $this->belongsToMany(Department::class)
                    ->withTimestamps();
    }
    public function assignments()
    {
        return $this->hasMany(DocumentAssignment::class);
    }
    public function surveys()
    {
        return $this->belongsToMany(Survey::class, 'survey_users')
                    ->withPivot('status')
                    ->withTimestamps();
    }
    public function faq()
    {
        return $this->hasOne(FaqVideouser::class);
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
           'email_verified_at' => 'datetime',
            'password' => 'hashed',
            //'phone' => 'integer',
            'date_of_birth' => 'date',
            'opt_for_daily' => 'boolean',
            'status' => UserStatus::class,
            // 'first_name' => 'encrypted',
            // 'last_name' => 'encrypted',
            // 'phone' => 'encrypted',
            // 'email' => 'encrypted',
        ];
    }
    private function decodeValue($value)
    {
        if (is_null($value)) return null;
        if ($value === 'N;') return null;

        // Step 1: Try decrypt
        try {
            $value = \Crypt::decryptString($value);
        } catch (\Exception $e) {
            // not encrypted, continue
        }

        // Step 2: Check if serialized
        if (is_string($value) && preg_match('/^(s|a|i|b|O):/', $value)) {
            $un = @unserialize($value);

            if ($un !== false || $value === 'b:0;') {
                return $un;
            }
        }

        return $value;
    }

    public function getFirstNameAttribute($value)
    {
        return $this->decodeValue($value);
    }

    public function getLastNameAttribute($value)
    {
        return $this->decodeValue($value);
    }

    public function getPhoneAttribute($value)
    {
        return $this->decodeValue($value);
    }

    public function getEmailAttribute($value)
    {
        return $this->decodeValue($value);
    }

    // protected $appends = [
    //     'role',
    // ];

    // protected $with = [
    //     'media'
    // ];

    // public static function booted()
    // {
    //     parent::boot();
    //     static::saving(function ($model) {
    //         $model->created_by_id = \App\Helpers\Helpers::isUserLogin() ? \App\Helpers\Helpers::getCurrentUserId() : $model->id;
    //     });
    // }

    /**
     * Get the user's role.
     */
    public function getRoleAttribute()
    {
        return $this->roles->first()?->makeHidden(['created_at', 'updated_at', 'pivot']);
    }

    /**
     * Get the user's all permissions.
     */
    public function getPermissionAttribute()
    {
        return $this->getAllPermissions();
    }

   


    // public function country()
    // {
    //     return $this->belongsTo(Country::class,'country_id');
    // }
}
