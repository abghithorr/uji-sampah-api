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
        Schema::create('sampahs', function (Blueprint $table) {
            $table->id();
            $table->string('kepala_keluarga');
            $table->integer('no_rumah');
            $table->string('rt_rw');
            $table->integer('total_karung_sampah');
            $table->enum('kriteria', ['standar', 'collapse']);
            $table->date('tanggal_pengangkutan');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sampahs');
    }
};
