<?php

namespace App\Models;

use App\Traits\HasFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    use HasFile {
        file as picture;
    }

    protected $fillable = [
        'code',
        'name',
    ];

    protected static function booted()
    {
        static::deleting(function (self $category) {
            $category->products->each->delete();
            optional($category->picture)->delete();
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
