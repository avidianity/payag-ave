<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'from',
        'amount',
        'cost',
        'paid',
    ];

    protected $appends = ['total'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->whereIn('role', [User::ADMIN, User::EMPLOYEE]);
    }

    public function getTotalAttribute()
    {
        return $this->amount * $this->cost;
    }
}
