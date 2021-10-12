<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    const PAID = 'paid';
    const UNPAID = 'unpaid';
    const DEBT = 'debt';

    const STATUSES = [
        self::PAID,
        self::UNPAID,
        self::DEBT,
    ];

    protected $fillable = [
        'customer_id',
        'biller_id',
        'paid',
        'status',
    ];

    protected $casts = [
        'paid' => 'float',
    ];

    protected static function booted()
    {
        static::deleting(function (self $order) {
            $order->items->each->delete();
        });
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function biller()
    {
        return $this->belongsTo(User::class, 'biller_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getIdentifier()
    {
        return "order-{$this->id}-{$this->created_at->timestamp}";
    }
}
