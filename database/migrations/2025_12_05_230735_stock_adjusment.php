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
        Schema::create('stock_adjusments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Admin yang melakukan adjustment
            $table->enum('tipe', ['masuk', 'keluar', 'koreksi']); // Tipe penyesuaian
            $table->integer('jumlah'); // Jumlah perubahan (positif/negatif)
            $table->integer('stok_sebelum'); // Stok sebelum adjustment
            $table->integer('stok_sesudah'); // Stok setelah adjustment
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjustments');
    }
};