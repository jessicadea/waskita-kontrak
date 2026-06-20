<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'type_name',
        'spec',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function volumes()
    {
        return $this->hasMany(ProductVariantVolume::class);
    }
}