<?php

namespace App\Models;

use App\Services\DOMService;
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
        'description',
        'price',
        'cost',
        'quantity',
        'category_id'
    ];

    protected $casts = [
        'price' => 'float',
    ];

    protected static function booted()
    {
        static::deleting(function (self $product) {
            optional($product->picture)->delete();
            $product->items->each->delete();
            $product->purchases->each->delete();
        });
    }

    public function setDescriptionAttribute($description)
    {
        $this->attributes['description'] = app(DOMService::class)->clean($description);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
