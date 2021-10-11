<?php

namespace App\Models;

use App\Notifications\VerifyEmail;
use App\Traits\HasFile;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasFile {
        file as picture;
    }

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

    protected static function booted()
    {
        static::deleting(function (self $user) {
            $user->changeEmailRequests->each->delete();
            $user->ordersAsCustomer->each->delete();
            $user->ordersAsBiller->each->delete();

            if ($user->isStaff()) {
                $user->purchases->each->delete();
            }

            optional($user->picture)->delete();
        });
    }

    public function routeNotificationForSemaphore($notifiable)
    {
        return $this->phone;
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail($this));
    }

    public function changeEmailRequests()
    {
        return $this->hasMany(ChangeEmailRequest::class);
    }

    public function getLockingKey()
    {
        return "{$this->id}-lock";
    }

    public function incrementLock()
    {
        $key = $this->getLockingKey();
        Cache::set($key, ((int)Cache::get($key, 0)) + 1, config('auth.blocking.seconds') + (config('auth.blocking.minutes') * 60));
    }

    public function resetLock()
    {
        Cache::delete($this->getLockingKey());
    }

    /**
     * Filter by role
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $role
     * @return void
     */
    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Filter by roles
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string[] $roles
     * @return void
     */
    public function scopeRoles($query, $roles)
    {
        return $query->whereIn('role', $roles);
    }

    public function isAdmin()
    {
        return $this->role === static::ADMIN;
    }

    public function isCustomer()
    {
        return $this->role === static::CUSTOMER;
    }

    public function isEmployee()
    {
        return $this->role === static::EMPLOYEE;
    }

    public function isStaff()
    {
        return !$this->isCustomer();
    }

    public function ordersAsCustomer()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function ordersAsBiller()
    {
        return $this->hasMany(Order::class, 'biller_id');
    }

    public function purchases()
    {
        if ($this->role === static::CUSTOMER) {
            throw new \RuntimeException('User is not a staff.');
        }
        return $this->hasMany(Purchase::class);
    }
}
