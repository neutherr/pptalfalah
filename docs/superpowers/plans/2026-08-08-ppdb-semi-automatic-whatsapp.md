# PPDB Semi-Automatic WhatsApp Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Mempermudah peninjauan dan komunikasi PPDB tanpa Fonnte melalui badge Filament dan tautan WhatsApp dengan pesan siap kirim.

**Architecture:** Tidak ada API, queue, token, atau dependency baru. Halaman bukti membuat tautan `wa.me` ke nomor pondok, sedangkan Filament membuat tautan `wa.me` ke kontak utama pendaftar setelah status berubah menjadi **Sudah Ditinjau**.

**Tech Stack:** Laravel 12, Livewire 3, Filament 3.3, Blade, PHPUnit.

## Global Constraints

- Status tetap hanya `new` dan `reviewed`.
- Tidak mengirim pesan otomatis; pengguna tetap menekan **Kirim** di WhatsApp.
- Tidak menambah package, migration, tabel, atau konfigurasi Fonnte.
- Pesan tidak memuat NIK, Nomor KK, NISN, alamat, foto, atau token publik.
- Nomor WhatsApp dinormalisasi ke format Indonesia `62...`.

---

### Task 1: Kunci Perilaku dengan Tes

**Files:**
- Modify: `tests/Feature/PpdbRegistrationAdminTest.php`
- Modify: `tests/Feature/PpdbRegistrationDataTest.php`

**Interfaces:**
- Consumes: `PpdbRegistrationResource`, route `ppdb.proof`.
- Produces: ekspektasi badge, URL WhatsApp admin, visibilitas aksi, dan copy bukti.

- [x] Tambahkan tes badge hanya menghitung status `new` dan kosong ketika nol.
- [x] Tambahkan tes normalisasi nomor `08...` menjadi `628...`.
- [x] Tambahkan tes URL konfirmasi memuat nama dan nomor pendaftaran, tanpa data sensitif.
- [x] Tambahkan tes aksi WhatsApp hanya tersedia setelah status `reviewed`.
- [x] Tambahkan tes halaman bukti menampilkan status menunggu peninjauan dan label **Beri Tahu Panitia via WhatsApp**.
- [x] Jalankan kedua file tes dan pastikan gagal karena fitur belum ada.

### Task 2: Tambahkan Badge dan Tautan WhatsApp Filament

**Files:**
- Modify: `app/Filament/Resources/PpdbRegistrationResource.php`
- Modify: `app/Filament/Resources/PpdbRegistrationResource/Pages/ViewPpdbRegistration.php`

**Interfaces:**
- Produces: `getNavigationBadge()`, `getNavigationBadgeColor()`, dan `contactWhatsappUrl(PpdbRegistration $registration): string`.

- [x] Hitung badge dari jumlah pendaftar berstatus `new`; kembalikan `null` jika nol.
- [x] Buat helper minimal untuk normalisasi nomor dan pesan konfirmasi.
- [x] Tambahkan aksi **Hubungi via WhatsApp** pada tabel, hanya untuk status `reviewed`, dan buka tab baru.
- [x] Tambahkan aksi **Tandai Ditinjau** serta **Hubungi via WhatsApp** pada halaman detail.
- [x] Pastikan `reviewed_at` tetap disimpan saat status ditinjau.
- [x] Jalankan tes admin sampai lulus.

### Task 3: Perjelas Bukti Pendaftaran

**Files:**
- Modify: `resources/views/pages/ppdb-proof.blade.php`

**Interfaces:**
- Consumes: `SiteSetting.whatsapp_number` dan data pendaftaran aman.
- Produces: pesan calon santri ke panitia dan copy menunggu peninjauan.

- [x] Ubah copy menjadi data berhasil diterima dan menunggu peninjauan.
- [x] Ubah pesan WhatsApp agar meminta panitia meninjau nama dan nomor pendaftaran.
- [x] Jadikan **Beri Tahu Panitia via WhatsApp** tombol utama; cetak bukti menjadi aksi sekunder.
- [x] Pertahankan instruksi berkas fisik dan larangan mengunggah dokumen sensitif.
- [x] Jalankan tes bukti pendaftaran sampai lulus.

### Task 4: Verifikasi Akhir

**Files:**
- Verify: `issue.md`
- Verify: seluruh file yang diubah.

- [x] Jalankan `php artisan test tests/Feature/PpdbRegistrationAdminTest.php tests/Feature/PpdbRegistrationDataTest.php`.
- [x] Jalankan seluruh tes PPDB yang relevan.
- [x] Jalankan `vendor/bin/pint --dirty`.
- [x] Jalankan `git diff --check`.
- [x] Tinjau diff untuk memastikan tidak ada Fonnte, data sensitif, atau perubahan di luar scope.
