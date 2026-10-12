# Mengubah konten beranda

Setelah kode terbaru di-deploy, buka `/admin/homepage-settings` atau menu **Konten Utama → Konten Beranda**.

1. Klik **Muat revisi klien** untuk mengisi judul hero, tagline, deskripsi sekolah, visi, delapan misi, dan moto sesuai bahan revisi 12 Oktober 2026.
2. Periksa seluruh teks. Tombol ini hanya mengisi formulir dan mengganti perubahan yang belum disimpan di formulir tersebut.
3. Klik **Simpan perubahan** untuk menerapkan semua isian ke website.
4. Periksa beranda pada desktop dan ponsel.

Judul misi bersifat opsional; bahan revisi menggunakan isi misi tanpa judul tambahan. Kosongkan judul dan isi untuk menyembunyikan satu poin. Moto dan deskripsi hero juga dapat dikosongkan. Gunakan Enter untuk pergantian baris, bukan HTML.

Gambar tetap dikelola di **Hero Section**. Pengaturan Situs tetap tersedia untuk pengaturan lainnya; gunakan Konten Beranda untuk teks beranda agar judul hero tersimpan terpisah dari visi.

## Foto / video Who We Are

Di **Konten Beranda → Media Who We Are**, pilih **Gambar** atau **Video**.

- Gambar: unggah JPG, PNG, atau WebP maksimal 2 MB. Tanpa unggahan, foto tanaman bawaan tetap tampil.
- Video: masukkan tautan YouTube (watch, youtu.be, embed, shorts, atau live) atau URL HTTPS langsung ke file MP4/WebM. File video harus sudah tersedia di penyimpanan publik; unggahan video langsung belum disediakan.
- Gambar yang diunggah menjadi sampul untuk video MP4/WebM. YouTube menggunakan sampul dari YouTube.
- Klik **Simpan perubahan**. Video memiliki kontrol putar dan tidak autoplay. Rasio pemutar 16:9 mengikuti lebar layar.

Beralih ke gambar mempertahankan tautan video untuk dipakai kembali. **Muat revisi klien** hanya mengubah teks, bukan pilihan media atau unggahan yang sedang disiapkan. Video YouTube harus mengizinkan penyematan; URL MP4/WebM harus dapat diakses publik dan memakai codec yang didukung browser.

Untuk gambar unggahan, pastikan `php artisan storage:link` sudah dijalankan dan `APP_URL` menggunakan domain HTTPS website yang benar.

## Deployment

Tidak ada perubahan skema database, dependensi, maupun migrasi data otomatis. Pembaruan kode mempertahankan nilai lama sampai administrator menyimpan form. Semua field baru dibuat melalui penyimpanan tersebut.

Deploy commit `main` berikut aset `public/build` yang sudah dibangun, lalu jalankan:

```sh
php artisan optimize:clear
php artisan view:cache
```

Jangan menjalankan ulang `SiteSettingSeeder` untuk menerapkan revisi: seeder lama menimpa pengaturan situs. Gunakan tombol Muat revisi klien di halaman baru.
