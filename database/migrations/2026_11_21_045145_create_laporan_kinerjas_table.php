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
        Schema::create('laporan_kinerjas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal_laporan');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->text('uraian_kegiatan');
            $table->string('hasil_output');
            $table->decimal('volume_kuantitas', 8, 2)->default(1);
            $table->string('satuan');
            $table->string('bukti_dukung_path')->nullable();
            $table->enum('status_verifikasi', ['terkirim', 'disetujui', 'revisi'])->default('terkirim');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_kinerjas');
    }
};
