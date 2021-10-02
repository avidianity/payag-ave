<?php

namespace App\Models;

use App\Notifications\ReVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeEmailRequest extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'approved'];

    protected static function booted()
    {
        static::created(function (self $request) {
            $request->user->notify(new ReVerifyEmail($request));
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
