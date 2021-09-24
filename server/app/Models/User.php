<?php

namespace App\Models;

use App\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    const ADMIN = 'admin';
    const EMPLOYEE = 'employee';
    const CUSTOMER = 'customer';

    const ROLES = [
        self::ADMIN,
        self::EMPLOYEE,
        self::CUSTOMER,
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'role',
        'phone',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => 'boolean',
    ];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail($this));
    }

    public function changeEmailRequests()
    {
        return $this->hasMany(ChangeEmailRequest::class);
    }
}
