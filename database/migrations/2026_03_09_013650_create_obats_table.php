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
        Schema::create('obat', function (Blueprint $table) {
           $table->id();
            $table->string('kode_obat')->unique();
            $table->string('nama_obat');
            $table->string('satuan');           // tablet, kapsul, ml, dll
            $table->integer('stok')->default(0);
            $table->decimal('harga', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obats');
    }
};
