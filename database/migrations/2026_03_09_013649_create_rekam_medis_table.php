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
        Schema::create('rekam_medis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kunjungan_id')->constrained('kunjungan')->cascadeOnDelete();
            $table->foreignId('pasien_id')->constrained('pasien');
            $table->foreignId('dokter_id')->constrained('users');
            $table->text('anamnesis');           // Keluhan utama
            $table->text('pemeriksaan_fisik')->nullable();
            $table->string('tekanan_darah')->nullable();
            $table->decimal('suhu', 4, 1)->nullable();
            $table->integer('nadi')->nullable();
            $table->integer('respirasi')->nullable();
            $table->decimal('berat_badan', 5, 2)->nullable();
            $table->text('diagnosis');
            $table->string('kode_icd')->nullable();     // Kode ICD-10
            $table->text('tindakan')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekam_medis');
    }
};
