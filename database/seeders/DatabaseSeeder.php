<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        CategoriSeeder::class;

        // Create Users
        User::create([
            'name' => 'Owner',
            'email' => 'owner@ertiga.com',
            'password' => Hash::make('password'),
            'role' => 'owner'
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@ertiga.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Kasir 1',
            'email' => 'kasir@ertiga.com',
            'password' => Hash::make('password'),
            'role' => 'kasir'
        ]);

        // ====================
        // Produk + Category ID
        // ====================

        $products = [
            // Makanan → categories_id = 1
            ['nama_produk' => 'Nasi Goreng', 'kategori' => 'Makanan', 'categories_id' => 1, 'harga_beli' => 10000, 'harga_jual' => 15000, 'stok' => 50],
            ['nama_produk' => 'Mie Goreng', 'kategori' => 'Makanan', 'categories_id' => 1, 'harga_beli' => 8000, 'harga_jual' => 12000, 'stok' => 50],
            ['nama_produk' => 'Ayam Geprek', 'kategori' => 'Makanan', 'categories_id' => 1, 'harga_beli' => 12000, 'harga_jual' => 18000, 'stok' => 30],
            ['nama_produk' => 'Sate Ayam', 'kategori' => 'Makanan', 'categories_id' => 1, 'harga_beli' => 15000, 'harga_jual' => 22000, 'stok' => 25],
            ['nama_produk' => 'Bakso', 'kategori' => 'Makanan', 'categories_id' => 1, 'harga_beli' => 9000, 'harga_jual' => 13000, 'stok' => 40],

            // Minuman → categories_id = 2
            ['nama_produk' => 'Es Teh Manis', 'kategori' => 'Minuman', 'categories_id' => 2, 'harga_beli' => 2000, 'harga_jual' => 5000, 'stok' => 100],
            ['nama_produk' => 'Jus Jeruk', 'kategori' => 'Minuman', 'categories_id' => 2, 'harga_beli' => 5000, 'harga_jual' => 10000, 'stok' => 50],
            ['nama_produk' => 'Kopi Hitam', 'kategori' => 'Minuman', 'categories_id' => 2, 'harga_beli' => 3000, 'harga_jual' => 8000, 'stok' => 80],
            ['nama_produk' => 'Cappuccino', 'kategori' => 'Minuman', 'categories_id' => 2, 'harga_beli' => 7000, 'harga_jual' => 15000, 'stok' => 60],
            ['nama_produk' => 'Milkshake', 'kategori' => 'Minuman', 'categories_id' => 2, 'harga_beli' => 8000, 'harga_jual' => 18000, 'stok' => 35],

            // Tambahan → categories_id = 3
            ['nama_produk' => 'Kerupuk', 'kategori' => 'Tambahan', 'categories_id' => 3, 'harga_beli' => 1000, 'harga_jual' => 2000, 'stok' => 200],
            ['nama_produk' => 'Sambal Extra', 'kategori' => 'Tambahan', 'categories_id' => 3, 'harga_beli' => 1500, 'harga_jual' => 3000, 'stok' => 150],
            ['nama_produk' => 'Telur Mata Sapi', 'kategori' => 'Tambahan', 'categories_id' => 3, 'harga_beli' => 3000, 'harga_jual' => 5000, 'stok' => 100],
        ];

        foreach ($products as $product) {
            Product::create(array_merge($product, ['satuan' => 'pcs']));
        }
    }
}
