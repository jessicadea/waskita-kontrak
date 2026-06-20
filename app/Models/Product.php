<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_name',
        'description',
        'status',
        'category',
        'unit',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}