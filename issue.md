# [PENDING] Notifikasi WhatsApp PPDB melalui Fonnte

## Status Saat Ini

Mode semi-otomatis tanpa Fonnte telah diimplementasikan secara lokal pada 8 Agustus 2026 dan belum di-deploy. Integrasi Fonnte **ditunda** dan belum menjadi bagian dari operasional PPDB saat ini. Website menggunakan alur tanpa notifikasi WhatsApp otomatis sampai pihak pondok mengonfirmasi nomor resmi, penanggung jawab, nomor admin penerima notifikasi, dan kepemilikan akun Fonnte.

Tidak ada akun atau token Fonnte yang perlu dibuat oleh developer pada tahap ini. Seluruh konfigurasi Fonnte harus tetap kosong atau nonaktif dan tidak boleh menghambat pendaftaran online.

## Alur Aktif Tanpa Fonnte

1. Calon santri mengisi dan mengirim formulir PPDB online.
2. Sistem memvalidasi data, menyimpan pendaftaran dengan status **Baru**, dan membuat nomor pendaftaran.
3. Calon santri diarahkan ke halaman **Bukti Pendaftaran** yang menyatakan data berhasil diterima dan masih menunggu peninjauan panitia.
4. Tombol utama **Beri Tahu Panitia via WhatsApp** membuka pesan siap kirim berisi nama dan nomor pendaftaran. Calon santri tetap harus menekan **Kirim** di WhatsApp.
5. Navigasi **Pendaftar PPDB** di Filament menampilkan badge jumlah pendaftar berstatus **Baru**.
6. Admin membuka data, memeriksa kelengkapannya, lalu menekan **Tandai Ditinjau**.
7. Setelah berstatus **Sudah Ditinjau**, tombol **Hubungi via WhatsApp** tersedia pada tabel dan halaman detail Filament.
8. Tombol tersebut membuka pesan konfirmasi siap kirim ke kontak utama calon santri. Admin tetap harus menekan **Kirim** di WhatsApp.
9. Calon santri datang ke pondok untuk mengikuti tes dan melengkapi persyaratan fisik.

Status tetap hanya **Baru** dan **Sudah Ditinjau**. Tidak ada status tambahan untuk tes, pembayaran, diterima, atau ditolak.

## Pesan Siap Kirim

### Dari Calon Santri ke Panitia

```text
Assalamu'alaikum Panitia PPDB PPT Al-Falah.

Saya sudah mengisi pendaftaran online atas nama {nama_calon_santri} dengan nomor pendaftaran {nomor_pendaftaran}. Mohon ditinjau. Terima kasih.
```

### Dari Admin ke Calon Santri

```text
Assalamu'alaikum Bapak/Ibu.

Pendaftaran online ananda {nama_calon_santri} dengan nomor {nomor_pendaftaran} telah ditinjau oleh panitia PPDB PPT Al-Falah.

Silakan datang ke pondok untuk mengikuti tes akademik dan membawa fotokopi Akta Kelahiran dan KK, pas foto 3×4 sebanyak empat lembar, serta fotokopi rapor terakhir.

Gratis biaya pendidikan selama satu tahun pertama.

Informasi waktu kedatangan dapat ditanyakan dengan membalas pesan ini.
```

Kedua pesan tidak boleh memuat NIK, Nomor KK, NISN, alamat lengkap, foto, atau token bukti publik.

## Acceptance Criteria Mode Aktif

- [x] Bukti pendaftaran menyatakan data berhasil diterima dan menunggu peninjauan.
- [x] Tombol utama bukti berlabel **Beri Tahu Panitia via WhatsApp** dan memakai pesan siap kirim.
- [x] Keberhasilan pendaftaran tidak bergantung pada tombol WhatsApp.
- [x] Badge navigasi Filament menghitung hanya pendaftar berstatus **Baru** dan tidak tampil ketika jumlahnya nol.
- [x] Aksi **Tandai Ditinjau** tetap menyimpan `reviewed_at`.
- [x] Tombol **Hubungi via WhatsApp** hanya tampil setelah pendaftaran ditinjau.
- [x] Tombol WhatsApp tersedia pada tabel dan halaman detail Filament.
- [x] Nomor `08...` dinormalisasi menjadi format `628...` untuk tautan WhatsApp.
- [x] Pesan konfirmasi tidak memuat data sensitif.
- [x] Tidak ada akun, token, request HTTP, atau dependency Fonnte pada mode aktif.

## Dampak Penundaan Fonnte

- Pendaftaran online, bukti pendaftaran, penyimpanan foto, Filament, dan ekspor data tetap berfungsi.
- Tidak ada pesan yang terkirim otomatis; calon santri dan admin tetap menekan **Kirim** di WhatsApp.
- Badge Filament hanya terlihat ketika admin membuka panel, bukan notifikasi push.
- Rencana Fonnte dipertahankan sebagai fase lanjutan dan tidak boleh diaktifkan sebelum seluruh prasyarat terpenuhi.

## Latar Belakang

Pendaftaran PPDB online sudah menyimpan data calon santri, membuat nomor pendaftaran, dan menampilkan halaman bukti. Admin belum menerima notifikasi pendaftar baru, sedangkan calon santri belum menerima konfirmasi WhatsApp setelah datanya ditinjau.

Proses pondok tetap sederhana: calon santri mendaftar online, lalu datang untuk tes dan melengkapi berkas setelah memperoleh informasi panitia melalui WhatsApp. Status hanya **Baru** dan **Sudah Ditinjau**.

## Tujuan Fase Lanjutan

- Menggunakan satu device Fonnte yang terhubung ke nomor WhatsApp resmi pondok setelah kepemilikan dan izin penggunaannya dikonfirmasi pihak pondok.
- Nomor `6281510029919` masih merupakan calon nomor pondok dan belum boleh dianggap sebagai nomor pengirim sebelum diverifikasi.
- Mengirim notifikasi otomatis dari nomor resmi pondok ke nomor pribadi admin setelah pendaftaran tersimpan.
- Mengirim konfirmasi otomatis dari nomor pondok ke kontak utama calon santri setelah admin menandai data **Sudah Ditinjau**.
- Mempertahankan halaman bukti website sebagai tanda bahwa data berhasil diterima dan menunggu peninjauan.
- Mencegah data sensitif dikirim melalui WhatsApp.

## Alur Fase Lanjutan

1. Calon santri menyelesaikan formulir PPDB.
2. Sistem menyimpan pendaftaran dengan status **Baru** dan membuat nomor pendaftaran.
3. Setelah transaksi database berhasil, nomor pondok mengirim notifikasi Fonnte kepada nomor pribadi admin/pengurus.
4. Calon santri diarahkan ke halaman bukti dengan informasi bahwa data berhasil diterima dan menunggu peninjauan.
5. Admin membuka Filament, memeriksa data, lalu menekan **Tandai Sudah Ditinjau**.
6. Status berubah menjadi **Sudah Ditinjau** dan nomor pondok mengirim konfirmasi Fonnte otomatis ke nomor WhatsApp kontak utama calon santri.
7. Pesan konfirmasi mengarahkan calon santri untuk datang ke pondok, mengikuti tes, dan membawa berkas persyaratan.

## Isi Notifikasi Admin — Fase Lanjutan

```text
Pendaftar PPDB baru perlu ditinjau.

Nomor: {nomor_pendaftaran}
Nama: {nama_calon_santri}
Sekolah: {sekolah_asal}
Wilayah: {desa}, {kecamatan}, {kabupaten_kota}
Kontak: {nomor_whatsapp}

Buka data:
{tautan_filament}
```

Pesan tidak boleh memuat NIK, Nomor KK, NISN, alamat lengkap, foto, atau token bukti publik.

## Konfirmasi Calon Santri — Fase Lanjutan

Sebelum ditinjau, halaman bukti menampilkan bahwa data berhasil diterima dan masih menunggu pemeriksaan panitia. Pesan WhatsApp berikut baru dikirim setelah admin menandai pendaftaran **Sudah Ditinjau**:

```text
Assalamu'alaikum Bapak/Ibu.

Pendaftaran online ananda {nama_calon_santri} dengan nomor {nomor_pendaftaran} telah ditinjau dan dikonfirmasi oleh panitia PPDB PPT Al-Falah.

Silakan datang ke pondok untuk mengikuti tes akademik dan membawa:
- Fotokopi Akta Kelahiran dan Kartu Keluarga
- Pas foto 3×4 sebanyak empat lembar
- Fotokopi rapor terakhir

Gratis biaya pendidikan selama satu tahun pertama.

Informasi waktu kedatangan dapat ditanyakan dengan membalas pesan ini.
```

- Pesan dikirim ke `primary_contact_phone` yang dipilih saat pendaftaran.
- Tombol WhatsApp pada halaman bukti tetap tersedia untuk menghubungi panitia.
- Menampilkan bukti tidak bergantung pada keberhasilan pengiriman WhatsApp.

## Prasyarat Sebelum Aktivasi

Integrasi tidak boleh diaktifkan sampai pihak pondok menyelesaikan seluruh prasyarat berikut:

- Mengonfirmasi siapa pemilik atau penanggung jawab nomor `6281510029919`.
- Mengonfirmasi apakah nomor tersebut benar-benar nomor WhatsApp resmi pondok dan boleh dihubungkan ke layanan Fonnte.
- Menentukan nomor pribadi admin/pengurus yang akan menerima notifikasi pendaftar baru.
- Membuat akun Fonnte atas nama atau di bawah kendali pihak pondok, bukan akun pribadi developer.
- Menghubungkan device WhatsApp resmi pondok ke akun Fonnte.
- Menyerahkan token Fonnte kepada developer melalui kanal privat untuk dipasang langsung di environment production.

Developer tidak boleh membuat akun atas nama pondok, menghubungkan nomor yang kepemilikannya belum jelas, atau menyimpan token di Git, issue, dokumentasi, maupun percakapan publik.

## Integrasi Fonnte — Fase Lanjutan

Gunakan HTTP client bawaan Laravel dan endpoint resmi Fonnte tanpa package baru.

```dotenv
FONNTE_ENABLED=false
FONNTE_TOKEN=
FONNTE_ADMIN_NUMBER=
```

- `FONNTE_ENABLED` tetap `false` sampai seluruh prasyarat disetujui pihak pondok.
- Device Fonnte harus terhubung ke nomor WhatsApp resmi pondok yang sudah diverifikasi; token device tersebut menjadi pengirim kedua jenis pesan.
- `FONNTE_ADMIN_NUMBER` berisi nomor pribadi admin/pengurus yang menerima pemberitahuan pendaftar baru.
- Nomor calon santri diambil dari `primary_contact_phone`, bukan dari environment.
- Tambahkan konfigurasi ke `config/services.php` dan nama variabel tanpa rahasia ke `.env.example`.
- Token tidak boleh masuk source code, database, log, atau respons pengguna.
- Nomor tujuan menggunakan format internasional, misalnya `6281234567890`.
- Ketika integrasi nonaktif atau konfigurasi belum lengkap, pendaftaran dan perubahan status tetap berjalan tanpa request ke Fonnte.
- Notifikasi admin dikirim setelah transaksi pendaftaran berhasil.
- Konfirmasi calon santri dikirim setelah perubahan status menjadi **Sudah Ditinjau** berhasil disimpan.
- Gunakan timeout pendek dan retry terbatas agar proses tidak menggantung.

## Penanganan Kegagalan — Fase Lanjutan

- Kegagalan notifikasi admin tidak membatalkan pendaftaran.
- Calon santri tetap diarahkan ke halaman bukti.
- Kegagalan konfirmasi calon santri tidak mengembalikan status dari **Sudah Ditinjau** menjadi **Baru**.
- Log hanya mencatat nomor pendaftaran, jenis pesan, nomor tujuan tersamarkan, dan penyebab kegagalan tanpa token atau data sensitif.
- Error teknis Fonnte tidak ditampilkan kepada calon santri.
- Jika konfirmasi gagal, admin dapat menghubungi calon santri secara manual dari data Filament.
- Tidak menggunakan queue worker pada versi pertama.

## Acceptance Criteria Fase Lanjutan

- [ ] Pendaftaran valid tersimpan dengan status **Baru**.
- [ ] Integrasi Fonnte nonaktif secara default.
- [ ] Tidak ada request Fonnte ketika integrasi nonaktif atau konfigurasinya belum lengkap.
- [ ] Nomor resmi pondok dan nomor pribadi admin telah dikonfirmasi pihak pondok sebelum aktivasi.
- [ ] Nomor pondok mengirim notifikasi otomatis ke nomor pribadi admin setelah transaksi database berhasil.
- [ ] Notifikasi admin berisi nomor, nama, sekolah, wilayah ringkas, kontak utama, dan tautan Filament.
- [ ] Halaman bukti menyatakan data berhasil diterima dan menunggu peninjauan.
- [ ] Aksi **Tandai Sudah Ditinjau** menyimpan status sebelum mengirim konfirmasi.
- [ ] Nomor pondok mengirim konfirmasi otomatis ke kontak utama calon santri setelah status berubah.
- [ ] Konfirmasi memuat nomor pendaftaran dan instruksi datang ke pondok.
- [ ] Kedua pesan tidak memuat data sensitif atau token bukti publik.
- [ ] Kegagalan atau timeout Fonnte tidak menggagalkan pendaftaran maupun perubahan status.
- [ ] Kegagalan pengiriman tercatat aman di log.
- [ ] Token dan nomor admin dibaca dari environment.
- [ ] Tombol WhatsApp pada bukti tetap berfungsi.

## Pengujian Fase Lanjutan

- Test tidak ada request Fonnte ketika `FONNTE_ENABLED=false`.
- Test tidak ada request Fonnte ketika token atau nomor admin belum tersedia.
- Test notifikasi admin dikirim setelah pendaftaran berhasil ketika integrasi aktif.
- Test notifikasi admin tidak dikirim ketika validasi atau penyimpanan gagal.
- Test halaman bukti menyatakan pendaftaran menunggu peninjauan.
- Test konfirmasi calon santri hanya dikirim ketika status berubah dari **Baru** menjadi **Sudah Ditinjau**.
- Test mengubah status yang sudah ditinjau tidak mengirim pesan konfirmasi kedua.
- Test nomor tujuan konfirmasi berasal dari `primary_contact_phone`.
- Test kedua payload tidak mengandung data sensitif.
- Test error dan timeout Fonnte tidak membatalkan pendaftaran atau perubahan status.
- Test token Fonnte tidak muncul di log atau respons pengguna.
- Test calon santri tetap diarahkan ke halaman bukti.

## Di Luar Scope

- Status tes, pembayaran, diterima, atau ditolak.
- Chatbot, balasan otomatis, dan pengingat jadwal.
- Queue worker dan retry background.
- Upload berkas persyaratan melalui website.
