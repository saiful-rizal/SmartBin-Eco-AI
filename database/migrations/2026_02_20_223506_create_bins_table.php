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
    Schema::create('bins', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Nama Lokasi (misal: Lobby A)
        $table->string('type'); // Tipe Sampah (Organik/Anorganik)
        $table->integer('current_weight')->default(0); // Berat sekarang (gram)
        $table->integer('max_capacity')->default(5000); // Kapasitas maksimal (gram)
        $table->integer('total_points')->default(0); // Total poin terkumpul
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bins');
    }
};
