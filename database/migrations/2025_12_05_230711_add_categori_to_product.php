<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Ubah kolom kategori menjadi category_id
            $table->foreignId('categories_id')->nullable()->after('nama_produk')->constrained('categories')->onDelete('set null');
            
            // Jika kolom kategori lama masih ada (string), bisa di-drop atau rename
            // $table->dropColumn('kategori'); // Uncomment jika ingin hapus kolom lama
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['categories_id']);
            $table->dropColumn('categories_id');
        });
    }
};