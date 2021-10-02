<?php

namespace App\Models;

use App\Traits\HasFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    use HasFile {
        file as picture;
    }

    protected $fillable = [
        'code',
        'name',
        'price',
        'cost',
        'quantity',
        'category_id'
    ];

    protected static function booted()
    {
        static::deleting(function (self $product) {
            $product->orders->each->delete();
            optional($product->picture)->delete();
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class)
            ->withTimestamps()
            ->using(OrderProduct::class);
    }
}
