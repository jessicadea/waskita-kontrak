<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantVolume;
use App\Models\ProductWorkStandard;
use Illuminate\Database\Seeder;

class ProductWorkStandardSeeder extends Seeder
{
    public function run(): void
    {
        $standards = [
            'Spun Pile' => [
                'capacity_per_day' => 10,
                'workers_per_team' => 5,
                'production_lead_days' => 14,
            ],
            'Square Pile' => [
                'capacity_per_day' => 8,
                'workers_per_team' => 5,
                'production_lead_days' => 14,
            ],
            'PC-I Girder' => [
                'capacity_per_day' => 2,
                'workers_per_team' => 8,
                'production_lead_days' => 21,
            ],
            'PC-U Girder' => [
                'capacity_per_day' => 2,
                'workers_per_team' => 8,
                'production_lead_days' => 21,
            ],
            'PC-T Girder' => [
                'capacity_per_day' => 1,
                'workers_per_team' => 10,
                'production_lead_days' => 30,
            ],
            'Box Culvert' => [
                'capacity_per_day' => 12,
                'workers_per_team' => 4,
                'production_lead_days' => 10,
            ],
            'U-Ditch & Cover' => [
                'capacity_per_day' => 15,
                'workers_per_team' => 4,
                'production_lead_days' => 10,
            ],
            'Readymix Concrete' => [
                'capacity_per_day' => 100,
                'workers_per_team' => 3,
                'production_lead_days' => 3,
            ],
        ];

        foreach ($standards as $productName => $standard) {
            $product = Product::where('product_name', $productName)->first();

            if (!$product) {
                continue;
            }

            $variants = ProductVariant::where('product_id', $product->id)->get();

            foreach ($variants as $variant) {
                $volumes = ProductVariantVolume::where('product_variant_id', $variant->id)->get();

                foreach ($volumes as $volume) {
                    ProductWorkStandard::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'variant_id' => $variant->id,
                            'volume_id' => $volume->id,
                        ],
                        $standard
                    );
                }
            }
        }
    }
}