# Mengubah konten beranda

Setelah kode terbaru di-deploy, buka `/admin/homepage-settings` atau menu **Konten Utama → Konten Beranda**.

1. Klik **Muat revisi klien** untuk mengisi judul hero, tagline, deskripsi sekolah, visi, delapan misi, dan moto sesuai bahan revisi 12 Oktober 2026.
2. Periksa seluruh teks. Tombol ini hanya mengisi formulir dan mengganti perubahan yang belum disimpan di formulir tersebut.
3. Klik **Simpan perubahan** untuk menerapkan semua isian ke website.
4. Periksa beranda pada desktop dan ponsel.

Judul misi bersifat opsional; bahan revisi menggunakan isi misi tanpa judul tambahan. Kosongkan judul dan isi untuk menyembunyikan satu poin. Moto dan deskripsi hero juga dapat dikosongkan. Gunakan Enter untuk pergantian baris, bukan HTML.

Gambar tetap dikelola di **Hero Section**. Pengaturan Situs tetap tersedia untuk pengaturan lainnya; gunakan Konten Beranda untuk teks beranda agar judul hero tersimpan terpisah dari visi.

## Deployment

Tidak ada perubahan skema database, dependensi, maupun migrasi data otomatis. Pembaruan kode mempertahankan nilai lama sampai administrator menyimpan form. Semua field baru dibuat melalui penyimpanan tersebut.

Deploy commit `main` berikut aset `public/build` yang sudah dibangun, lalu jalankan:

```sh
php artisan optimize:clear
php artisan view:cache
```

Jangan menjalankan ulang `SiteSettingSeeder` untuk menerapkan revisi: seeder lama menimpa pengaturan situs. Gunakan tombol Muat revisi klien di halaman baru.
