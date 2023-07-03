<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'status'
    ];

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'email_verification_token_expires_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    //verified email
    public function generateEmailVerificationToken()
    {
        $this->email_verification_token = bin2hex(random_bytes(32));
        $this->email_verification_token_expires_at = Carbon::now()->addDay();
        $this->save();
    }

    public function getEmailVerificationUrl()
    {
        return URL::temporarySignedRoute(
            'email.verify',
            $this->email_verification_token_expires_at,
            ['user' => $this->id]
        );
    }

    public function markEmailAsVerified()
    {
        $this->is_verified = true;
        // $this->email_verification_token = null;
        // $this->email_verification_token_expires_at = null;
        $this->email_verified_at = Carbon::now();
        $this->save();
    }
}
