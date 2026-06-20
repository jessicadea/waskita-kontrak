<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantVolume extends Model
{
    protected $fillable = [
        'product_variant_id',
        'volume_value',
        'unit',
        'description',
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}