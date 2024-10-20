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
        Schema::create('outcoming_letters', function (Blueprint $table) {
            $table->id();
            $table->string('nomor');
            $table->string('penerima');
            $table->string('perihal');
            $table->date('tanggal_dikirim');
            $table->date('tanggal_dibuat');
            $table->string('deskripsi');
            $table->string('attachment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outcoming_letters');
    }
};
