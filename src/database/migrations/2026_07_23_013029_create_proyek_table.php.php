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
        Schema::create('proyek', function (Blueprint $table) {

            // Primary Key
            $table->id('id_proyek');

            // Foreign Key ke tabel users
            $table->foreignId('id_user')
                ->constrained('users', 'id_user')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Informasi proyek
            $table->string('nama_proyek', 150);

            $table->text('tujuan_valuasi');

            $table->string('lokasi', 255);

            $table->year('tahun');

            $table->text('deskripsi')->nullable();

            // Status proyek
            $table->enum('status', [
                'Draft',
                'Proses',
                'Selesai',
                'Dibatalkan'
            ])->default('Draft');

            // Timestamp Laravel
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
