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
        Schema::create('ai_analisis', function (Blueprint $table) {
            $table->id();                       // PK ID (auto increment)
            $table->string('type');             // kolom type
            $table->timestamp('created_at');    // kolom created_at
            $table->text('result_text');        // hasil analisis teks
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_analisis');
    }
};
