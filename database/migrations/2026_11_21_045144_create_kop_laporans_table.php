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
        Schema::create('kop_laporans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pemerintah'); // contoh: PEMERINTAH KOTA SEMARANG
            $table->string('nama_kecamatan');  // contoh: KECAMATAN CANDISARI
            $table->string('nama_kelurahan');  // contoh: KELURAHAN TEGALSARI
            $table->string('alamat_kantor');
            $table->string('kontak_kantor')->nullable(); // no telp / email / web
            $table->string('logo_path')->nullable();     // upload gambar logo instansi
            $table->string('nama_lurah');
            $table->string('nip_lurah');
            $table->string('jabatan_penandatangan')->default('Lurah');
            $table->text('teks_pembuka_laporan')->nullable();
            $table->text('teks_penutup_laporan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kop_laporans');
    }
};
