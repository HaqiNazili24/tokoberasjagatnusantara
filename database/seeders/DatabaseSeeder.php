<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Owner
        User::updateOrCreate(
            ['email' => 'owner@jagatnusantara.test'],
            [
                'full_name' => 'Owner Jagat Nusantara',
                'phone' => '081234567891',
                'password' => Hash::make('owner123'),
                'role' => 'owner',
            ]
        );

        // 2. Karyawan
        User::updateOrCreate(
            ['email' => 'karyawan@jagatnusantara.test'],
            [
                'full_name' => 'Karyawan Jagat Nusantara',
                'phone' => '081234567892',
                'password' => Hash::make('karyawan123'),
                'role' => 'karyawan',
            ]
        );

        // 3. Kurir
        User::updateOrCreate(
            ['email' => 'kurir@jagatnusantara.test'],
            [
                'full_name' => 'Kurir Jagat Nusantara',
                'phone' => '081234567893',
                'password' => Hash::make('kurir123'),
                'role' => 'kurir',
            ]
        );

        // 4. Customer
        User::updateOrCreate(
            ['email' => 'customer@jagatnusantara.test'],
            [
                'full_name' => 'Budi Pelanggan',
                'phone' => '081298765432',
                'password' => Hash::make('customer123'),
                'role' => 'customer',
            ]
        );

        // Categories & Products
        $data = [
            'Beras' => [
                'Premium' => [
                    ['name' => 'Beras Idola', 'variants' => [25], 'price_per_kg' => 16000],
                ],
                'Medium' => [
                    ['name' => 'Beras Rojo Lele', 'variants' => [5, 10, 25], 'price_per_kg' => 13500],
                    ['name' => 'Beras Ramos Bandung', 'variants' => [5, 10, 25], 'price_per_kg' => 13000],
                    ['name' => 'Beras SPHP', 'variants' => [5], 'price_per_kg' => 11000],
                ],
            ],
        ];

        foreach ($data as $catName => $subCats) {
            $cat = Category::firstOrCreate(
                ['name' => $catName],
                ['slug' => Str::slug($catName)]
            );

            foreach ($subCats as $subName => $products) {
                $sub = SubCategory::firstOrCreate(
                    ['category_id' => $cat->id, 'name' => $subName],
                    ['slug' => Str::slug($catName . '-' . $subName)]
                );

                foreach ($products as $product) {
                    foreach ($product['variants'] as $kg) {
                        $name = $product['name'] . ' ' . $kg . 'kg';
                        $price = $kg * $product['price_per_kg'];

                        Product::firstOrCreate(
                            ['name' => $name],
                            [
                                'sub_category_id' => $sub->id,
                                'slug' => Str::slug($name) . '-' . uniqid(),
                                'description' => "{$product['name']} kemasan {$kg}kg. Kualitas {$subName} pilihan terbaik.",
                                'price' => $price,
                                'weight_label' => $kg . 'kg',
                                'stock' => 50,
                                'is_active' => true,
                            ]
                        );
                    }
                }
            }
        }

        $this->command->info('Seed selesai!');
        $this->command->info('Login Owner         : owner@jagatnusantara.test / owner123');
        $this->command->info('Login Karyawan      : karyawan@jagatnusantara.test / karyawan123');
        $this->command->info('Login Kurir         : kurir@jagatnusantara.test / kurir123');
        $this->command->info('Login Customer      : customer@jagatnusantara.test / customer123');
    }
}