<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
        protected $fillable = [
        'user_id',
        'product_id',
        'variant_id',
        'volume_id',
        'quantity',
        'company_name',
        'company_type',
        'project_name',
        'project_location',
        'product_spec',
        'volume',
        'delivery_cond',
        'delivery_date',
        'status_verify',
        'verify_note',
        'contract_file',
        'requires_acceleration',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function project()
    {
        return $this->hasOne(Project::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function selectedVolume()
    {
        return $this->belongsTo(ProductVariantVolume::class, 'volume_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

}