<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('pages')->insertOrIgnore([
            'title' => 'Kebijakan Privasi PPDB',
            'slug' => 'kebijakan-privasi-ppdb',
            'content' => <<<'HTML'
<h2>Tujuan Pengumpulan Data</h2>
<p>Data calon santri dan orang tua/wali digunakan untuk proses pendaftaran, verifikasi, seleksi, komunikasi, dan administrasi PPDB Pondok Pesantren Tahfidz Al-Falah.</p>
<h2>Akses Terbatas</h2>
<p>Akses data dibatasi kepada panitia dan pengelola sekolah yang berwenang. Data tidak digunakan untuk kepentingan di luar proses pendidikan dan administrasi tanpa dasar yang sah.</p>
<h2>Penyimpanan Foto dan Identitas</h2>
<p>Foto pendaftar disimpan pada penyimpanan privat. NIK dan NISN disimpan dalam bentuk terenkripsi serta hanya ditampilkan kepada petugas yang berwenang.</p>
<h2>Koreksi dan Penghapusan</h2>
<p>Orang tua atau wali dapat meminta koreksi atau penghapusan data dengan menghubungi sekolah melalui halaman Kontak. Permintaan akan diverifikasi terlebih dahulu untuk melindungi pemilik data.</p>
<h2>Masa Penyimpanan</h2>
<p>Data pendaftaran ditinjau untuk penghapusan satu tahun setelah periode PPDB berakhir, kecuali masih diperlukan untuk kewajiban administrasi atau hukum.</p>
<h2>Kontak</h2>
<p>Pertanyaan mengenai penggunaan data PPDB dapat disampaikan kepada Pondok Pesantren Tahfidz Al-Falah melalui kontak resmi sekolah.</p>
<p><strong>Catatan:</strong> Naskah ini harus ditinjau pihak sekolah sebelum peluncuran dan bukan pengganti konsultasi hukum.</p>
HTML,
            'meta_title' => 'Kebijakan Privasi PPDB | PPT Al-Falah',
            'meta_description' => 'Kebijakan pengumpulan, penggunaan, penyimpanan, koreksi, dan retensi data pendaftaran PPDB PPT Al-Falah.',
            'template' => 'default',
            'is_published' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void {}
};
