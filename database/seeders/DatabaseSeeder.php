<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ProductCatalogSeeder::class,
        ]);

        Product::updateOrCreate(
            ['product_name' => 'Beton Precast'],
            [
                'description' => 'Produk beton pracetak untuk kebutuhan konstruksi.',
                'status' => 'active',
            ]
        );

        Product::updateOrCreate(
            ['product_name' => 'Readymix Concrete'],
            [
                'description' => 'Beton siap pakai untuk proyek pembangunan.',
                'status' => 'active',
            ]
        );

        Product::updateOrCreate(
            ['product_name' => 'Spun Pile'],
            [
                'description' => 'Tiang pancang beton untuk pondasi.',
                'status' => 'active',
            ]
        );
    }
}