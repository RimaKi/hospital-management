<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    protected $appends = [
      "role"
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nationalId',
        'email',
        'birthday',
        'name',
        'phone',
        'password',
       'photo'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        "roles",
        'password',
        'remember_token',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function getDoctorAttribute() {
        return $this->hasOne(Doctor::class,'userId','id')->firstOrCreate();
    }
    public function getRoleAttribute() {
        $roles = $this->getRoleNames();
        return $roles->count() > 0 ? $roles->first() : "";
    }

    public function getPhotoAttribute() {
        if ($this->attributes["photo"] != null && $this->attributes["photo"] != "" && Storage::disk("public")->exists( $this->attributes["photo"]))
            return Storage::disk('public')->url($this->attributes["photo"]);
        return "";
    }
}

