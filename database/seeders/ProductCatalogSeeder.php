<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $catalogs = [
            [
                'product_name' => 'Spun Pile',
                'category' => 'Bina Marga',
                'description' => 'Tiang pancang beton prestressed untuk struktur dengan gaya aksial dan momen lentur.',
                'status' => 'active',
                'unit' => 'meter',
                'variants' => [
                    'D300' => [6, 9, 12, 13],
                    'D400' => [6, 9, 12, 15, 17],
                    'D500' => [6, 9, 12, 16, 19],
                    'D600' => [6, 9, 12, 17, 21],
                    'D800' => [6, 12, 18, 20, 24],
                    'D1000' => [6, 12, 18, 23, 28],
                    'D1200' => [6, 12, 18, 26, 30],
                ],
            ],
            [
                'product_name' => 'Square Pile',
                'category' => 'Bina Marga',
                'description' => 'Tiang pancang beton persegi untuk pondasi bangunan dan struktur dominan gaya aksial.',
                'status' => 'active',
                'unit' => 'meter',
                'variants' => [
                    '25x25' => [6, 9, 12, 14, 16],
                    '30x30' => [6, 9, 12, 14, 16, 18],
                    '35x35' => [6, 9, 12, 14, 16, 18],
                    '40x40' => [6, 9, 12, 14, 16, 18, 20],
                    '45x45' => [6, 9, 12, 14, 16, 18, 20],
                    '50x50' => [6, 9, 12, 14, 16, 18, 20],
                ],
            ],
            [
                'product_name' => 'PC-I Girder',
                'category' => 'Bina Marga',
                'description' => 'Balok girder pracetak berbentuk I untuk jembatan jalan dan rel.',
                'status' => 'active',
                'unit' => 'meter',
                'variants' => [
                    'H-90' => [16, 18, 19],
                    'H-125' => [16, 20, 24, 28],
                    'H-160' => [24, 30, 35, 40],
                    'H-170' => [30, 35, 40, 44],
                    'H-210' => [38, 40, 45, 50, 52],
                    'Semi-T' => [40, 45, 50],
                ],
            ],
            [
                'product_name' => 'PC-U Girder',
                'category' => 'Bina Marga',
                'description' => 'Balok girder pracetak berbentuk U dengan stabilitas lateral tinggi.',
                'status' => 'active',
                'unit' => 'meter',
                'variants' => [
                    'H-120' => [20],
                    'H-130' => [25],
                    'H-140' => [30],
                    'H-165' => [32],
                    'H-185' => [35, 40],
                    'H-220' => [50],
                    'H-240' => [50],
                ],
            ],
            [
                'product_name' => 'PC-T Girder',
                'category' => 'Bina Marga',
                'description' => 'Balok girder pracetak berbentuk T untuk bentang panjang.',
                'status' => 'active',
                'unit' => 'meter',
                'variants' => [
                    'Standard' => [50, 55, 60],
                ],
            ],
            [
                'product_name' => 'Voided Slab',
                'category' => 'Bina Marga',
                'description' => 'Slab beton pracetak pretension untuk jembatan bentang pendek.',
                'status' => 'active',
                'unit' => 'meter',
                'variants' => [
                    'Type 1 H-57' => [5, 10, 14, 15],
                    'Type 1 H-62' => [5, 10, 14, 15, 16],
                    'Type 1 H-66' => [5, 10, 14, 16, 17],
                    'Type 2 H-52.5' => [5, 10, 11, 12],
                    'Type 2 H-62.5' => [5, 10, 14, 15, 17],
                ],
            ],
            [
                'product_name' => 'Full Slab',
                'category' => 'Bina Marga',
                'description' => 'Slab pracetak pre-tension untuk struktur pile slab, jalan, dan ramp.',
                'status' => 'active',
                'unit' => 'unit',
                'variants' => [
                    'Type 1A' => [1],
                    'Type 2A' => [1],
                    'Type 1B' => [1],
                    'Type 2B' => [1],
                ],
            ],
            [
                'product_name' => 'Half Slab',
                'category' => 'Bina Marga',
                'description' => 'Slab semi-finished yang membutuhkan topping di permukaan.',
                'status' => 'active',
                'unit' => 'unit',
                'variants' => [
                    'Standard' => [1],
                    'Custom' => [1],
                ],
            ],
            [
                'product_name' => 'Movable Concrete Barrier (MCB)',
                'category' => 'Bina Marga',
                'description' => 'Barrier beton pracetak untuk median jalan, pembatas lajur, dan pagar proyek.',
                'status' => 'active',
                'unit' => 'unit',
                'variants' => [
                    'Type 1 - 600 kg' => [1],
                    'Type 2 - 260 kg' => [1],
                    'Type 3 - 580 kg' => [1],
                ],
            ],
            [
                'product_name' => 'Concrete Sleeper for Railway (BJR)',
                'category' => 'Bina Marga',
                'description' => 'Bantalan beton untuk jalur kereta api.',
                'status' => 'active',
                'unit' => 'unit',
                'variants' => [
                    'BJR-1067' => [1],
                    'BJR-1435' => [1],
                ],
            ],
            [
                'product_name' => 'Box Culvert',
                'category' => 'Irrigation',
                'description' => 'Beton pracetak berbentuk box untuk drainase dan saluran air.',
                'status' => 'active',
                'unit' => 'unit',
                'variants' => [
                    '1000x1000' => [1],
                    '1500x1500' => [1],
                    '2000x2000' => [1],
                    '2500x2500' => [1],
                ],
            ],
            [
                'product_name' => 'U-Ditch & Cover',
                'category' => 'Irrigation',
                'description' => 'Saluran beton pracetak berbentuk U beserta cover.',
                'status' => 'active',
                'unit' => 'unit',
                'variants' => [
                    'U-Ditch 300x300' => [1],
                    'U-Ditch 400x400' => [1],
                    'U-Ditch 600x600' => [1],
                    'U-Ditch 800x800' => [1],
                    'Cover U-Ditch' => [1],
                ],
            ],
            [
                'product_name' => 'RC Pipe',
                'category' => 'Irrigation',
                'description' => 'Pipa beton bertulang untuk saluran air.',
                'status' => 'active',
                'unit' => 'unit',
                'variants' => [
                    'D300' => [1],
                    'D400' => [1],
                    'D600' => [1],
                    'D800' => [1],
                    'D1000' => [1],
                ],
            ],
            [
                'product_name' => 'Tetrapod',
                'category' => 'Irrigation',
                'description' => 'Beton pelindung pantai dan pemecah gelombang.',
                'status' => 'active',
                'unit' => 'unit',
                'variants' => [
                    'Small' => [1],
                    'Medium' => [1],
                    'Large' => [1],
                ],
            ],
            [
                'product_name' => 'Beam-Column Precast',
                'category' => 'Housing & Building',
                'description' => 'Komponen balok dan kolom beton pracetak untuk bangunan.',
                'status' => 'active',
                'unit' => 'unit',
                'variants' => [
                    'Beam' => [1],
                    'Column' => [1],
                    'Beam-Column Set' => [1],
                ],
            ],
            [
                'product_name' => 'Facade',
                'category' => 'Housing & Building',
                'description' => 'Panel facade precast untuk bangunan.',
                'status' => 'active',
                'unit' => 'unit',
                'variants' => [
                    'Standard Panel' => [1],
                    'Custom Panel' => [1],
                ],
            ],
            [
                'product_name' => 'Readymix Concrete',
                'category' => 'Readymix Concrete',
                'description' => 'Beton siap pakai untuk kebutuhan konstruksi.',
                'status' => 'active',
                'unit' => 'm3',
                'variants' => [
                    'K-225' => [1],
                    'K-250' => [1],
                    'K-300' => [1],
                    'K-350' => [1],
                    'K-400' => [1],
                    'K-500' => [1],
                ],
            ],
        ];

        foreach ($catalogs as $item) {
            $product = Product::updateOrCreate(
                ['product_name' => $item['product_name']],
                [
                    'category' => $item['category'],
                    'description' => $item['description'],
                    'status' => $item['status'],
                    'unit' => $item['unit'],
                ]
            );

            foreach ($item['variants'] as $typeName => $volumes) {
                $variant = $product->variants()->updateOrCreate(
                    ['type_name' => $typeName],
                    [
                        'spec' => null,
                    ]
                );

                foreach ($volumes as $volume) {
                    $variant->volumes()->updateOrCreate(
                        [
                            'volume_value' => $volume,
                            'unit' => $item['unit'],
                        ],
                        [
                            'description' => $volume . ' ' . $item['unit'],
                        ]
                    );
                }
            }
        }
    }
}