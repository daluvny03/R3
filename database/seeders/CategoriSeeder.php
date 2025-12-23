<?php

namespace Database\Seeders;

use App\Models\Categories;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'nama_kategori' => 'Makanan',
                'deskripsi' => 'Produk makanan dan snack',
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Minuman',
                'deskripsi' => 'Berbagai jenis minuman',
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Tambahan',
                'deskripsi' => 'Tambahan lain-lain',
                'is_active' => true
            ],
        ];

        foreach ($categories as $category) {
            Categories::create($category);
        }
    }
}