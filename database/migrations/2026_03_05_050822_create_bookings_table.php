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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lapangan_id')->constrained('lapangans')->cascadeOnDelete();
            $table->string('nama_pelanggan');
            $table->string('no_hp');
            $table->date('tanggal');
            $table->string('jam');
            $table->integer('harga');
            $table->string('status')->default('pending'); // pending, approved, cancelled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
