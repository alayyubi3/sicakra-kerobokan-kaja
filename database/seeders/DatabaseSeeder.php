<?php

namespace Database\Seeders;

use App\Models\LaporanKinerja;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1 Akun Admin
        $admin = User::firstOrCreate(
            ['nip' => '198501152008011002'],
            [
                'name' => 'Hendro Prasetyo, S.STP.',
                'email' => 'admin@sicakra.id',
                'password' => Hash::make('password'),
                'jabatan' => 'Sekretaris Kelurahan / Administrator',
                'bidang_seksi' => 'Sekretariat',
                'role' => 'admin',
                'status_aktif' => true,
            ]
        );

        // Staff 1
        $staff1 = User::firstOrCreate(
            ['nip' => '199203142019021001'],
            [
                'name' => 'Ahmad Fauzi, S.AP.',
                'email' => 'ahmad.fauzi@sicakra.id',
                'password' => Hash::make('password'),
                'jabatan' => 'Pengadministrasi Pemerintahan',
                'bidang_seksi' => 'Pemerintahan & Ketentraman',
                'role' => 'staff',
                'status_aktif' => true,
            ]
        );

        // Staff 2
        $staff2 = User::firstOrCreate(
            ['nip' => '199507202020122003'],
            [
                'name' => 'Siti Rahmawati, A.Md.',
                'email' => 'siti.rahmawati@sicakra.id',
                'password' => Hash::make('password'),
                'jabatan' => 'Pengelola Pelayanan Publik',
                'bidang_seksi' => 'Pelayanan Umum & Kesejahteraan',
                'role' => 'staff',
                'status_aktif' => true,
            ]
        );

        // Kop Laporan
        $this->call(KopLaporanSeeder::class);

        // Sample Data Laporan Kinerja untuk testing dan tampilan langsung hidup
        $today = Carbon::today();

        $kegiatanStaff1 = [
            [
                'tanggal_laporan' => $today->copy()->subDays(2)->format('Y-m-d'),
                'jam_mulai' => '08:00',
                'jam_selesai' => '10:30',
                'uraian_kegiatan' => 'Melakukan verifikasi berkas permohonan surat keterangan domisili usaha dan izin keramaian lingkungan RT 04/RW 02.',
                'hasil_output' => 'Berkas terverifikasi lengkap',
                'volume_kuantitas' => 5,
                'satuan' => 'Berkas',
                'status_verifikasi' => 'disetujui',
                'catatan_admin' => 'Tugas dilaksanakan tepat waktu dan teliti.',
            ],
            [
                'tanggal_laporan' => $today->copy()->subDays(1)->format('Y-m-d'),
                'jam_mulai' => '09:00',
                'jam_selesai' => '12:00',
                'uraian_kegiatan' => 'Menghadiri rapat koordinasi ketentraman dan ketertiban umum bersama Babinsa, Bhabinkamtibmas, dan FKPM.',
                'hasil_output' => 'Notulen hasil rapat koordinasi',
                'volume_kuantitas' => 1,
                'satuan' => 'Kegiatan',
                'status_verifikasi' => 'disetujui',
                'catatan_admin' => 'Baik, teruskan komunikasi dengan stakeholder.',
            ],
            [
                'tanggal_laporan' => $today->format('Y-m-d'),
                'jam_mulai' => '08:30',
                'jam_selesai' => '11:00',
                'uraian_kegiatan' => 'Monitoring dan evaluasi kesiapan pos ronda dan sistem keamanan lingkungan warga RW 01 s/d RW 03.',
                'hasil_output' => 'Laporan rekap pantauan poskamling',
                'volume_kuantitas' => 3,
                'satuan' => 'Lokasi',
                'status_verifikasi' => 'terkirim',
                'catatan_admin' => null,
            ],
        ];

        foreach ($kegiatanStaff1 as $item) {
            LaporanKinerja::create(array_merge($item, ['user_id' => $staff1->id]));
        }

        $kegiatanStaff2 = [
            [
                'tanggal_laporan' => $today->copy()->subDays(1)->format('Y-m-d'),
                'jam_mulai' => '08:00',
                'jam_selesai' => '14:00',
                'uraian_kegiatan' => 'Melayani pengurusan Surat Pengantar Pembuatan KTP-el, KK baru, dan Surat Keterangan Pindah Masuk warga.',
                'hasil_output' => 'Surat pengantar diterbitkan dan dicatat dalam buku register',
                'volume_kuantitas' => 14,
                'satuan' => 'Layanan',
                'status_verifikasi' => 'disetujui',
                'catatan_admin' => 'Bagus, pertahankan kecepatan pelayanan masyarakat.',
            ],
            [
                'tanggal_laporan' => $today->format('Y-m-d'),
                'jam_mulai' => '08:30',
                'jam_selesai' => '12:30',
                'uraian_kegiatan' => 'Merekapitulasi permohonan data bantuan sosial PKH dan verifikasi DTKS warga rentan Kelurahan.',
                'hasil_output' => 'Draft daftar usulan warga penerima bansos',
                'volume_kuantitas' => 1,
                'satuan' => 'Dokumen',
                'status_verifikasi' => 'revisi',
                'catatan_admin' => 'Mohon lampirkan cek silang NIK kepala keluarga pada lampiran 2.',
            ],
        ];

        foreach ($kegiatanStaff2 as $item) {
            LaporanKinerja::create(array_merge($item, ['user_id' => $staff2->id]));
        }
    }
}
