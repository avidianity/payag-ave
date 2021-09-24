<?php

namespace App\Models;

use App\Jobs\SendChangeEmailMail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeEmailRequest extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'approved'];

    protected static function booted()
    {
        static::created(function (self $request) {
            dispatch(new SendChangeEmailMail($request->email, $request->user, $request));
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
