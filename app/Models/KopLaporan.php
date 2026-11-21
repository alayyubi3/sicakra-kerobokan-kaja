<?php

namespace App\Models;

use Database\Factories\KopLaporanFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KopLaporan extends Model
{
    /** @use HasFactory<KopLaporanFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'nama_pemerintah',
        'nama_kecamatan',
        'nama_kelurahan',
        'alamat_kantor',
        'kontak_kantor',
        'logo_path',
        'nama_lurah',
        'nip_lurah',
        'jabatan_penandatangan',
        'teks_pembuka_laporan',
        'teks_penutup_laporan',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public static function getActive(): ?self
    {
        return static::where('is_active', true)->latest('id')->first()
            ?: static::latest('id')->first();
    }
}
