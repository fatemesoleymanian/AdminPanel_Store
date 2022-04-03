<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded=['id'];
    use HasFactory;
    public function getCreatedAtAttribute($val)
    {
        return verta($val)->format('l d %B Y');
    }
    public function getUpdatedAtAttribute($val)
    {
        return verta($val)->format('l d %B Y');
    }
    public function category()
    {
        return $this->belongsToMany(Category::class,ProductCategory::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class,'brand_id');
    }
    public function color()
    {
            return $this->hasMany(productcolors::class,'product_id');
    }
    public function catFilter()
    {
        return $this->hasMany(ProductCategory::class,'product_id');
    }

}

