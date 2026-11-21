<?php

namespace App\Models;

use Database\Factories\LaporanKinerjaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanKinerja extends Model
{
    /** @use HasFactory<LaporanKinerjaFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'tanggal_laporan',
        'jam_mulai',
        'jam_selesai',
        'uraian_kegiatan',
        'hasil_output',
        'volume_kuantitas',
        'satuan',
        'bukti_dukung_path',
        'status_verifikasi',
        'catatan_admin',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_laporan' => 'date',
            'volume_kuantitas' => 'float',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isDisetujui(): bool
    {
        return $this->status_verifikasi === 'disetujui';
    }

    public function canBeEdited(): bool
    {
        return $this->status_verifikasi !== 'disetujui';
    }
}
