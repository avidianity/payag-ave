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

    protected static function booted()
    {
        static::deleting(function (self $product) {
            $product->products->each->delete();
        });
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withTimestamps()
            ->using(OrderProduct::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function biller()
    {
        return $this->belongsTo(User::class, 'biller_id');
    }

    public function getIdentifier()
    {
        return "order-{$this->id}-{$this->created_at->timestamp}";
    }
}
