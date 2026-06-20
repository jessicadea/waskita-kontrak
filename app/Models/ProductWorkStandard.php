<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductWorkStandard extends Model
{
    protected $fillable = [
        'product_id',
        'variant_id',
        'volume_id',
        'capacity_per_day',
        'workers_per_team',
        'production_lead_days',
    ];
}