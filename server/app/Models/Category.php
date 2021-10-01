<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    protected static function booted()
    {
        static::deleting(function (self $category) {
            $category->products->each->delete();
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
