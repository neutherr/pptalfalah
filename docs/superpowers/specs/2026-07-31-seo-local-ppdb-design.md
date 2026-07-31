# Desain SEO Lokal dan PPDB PPT Al-Falah

**Tanggal:** 31 Juli 2026
**Situs:** https://pptalfalah.com
**Prioritas:** PPDB, branding lembaga, lalu trafik organik dari konten yang sudah tersedia.

## Tujuan

- Memposisikan PPT Al-Falah sebagai **SMK Pertanian & IT berbasis Pesantren Tahfidz di Jonggol, Bogor**.
- Meningkatkan visibilitas halaman utama, program, dan PPDB untuk pencarian lokal.
- Membuat semua halaman penting mudah ditemukan, dipahami, dan diindeks mesin pencari.
- Mengarahkan pengunjung organik ke halaman PPDB dan kontak tanpa mengubah desain utama situs.

## Batasan

- Tidak bergantung pada penerbitan artikel rutin.
- Tidak membuat halaman tipis atau duplikat hanya untuk mengejar keyword.
- Tidak menjanjikan posisi tertentu di hasil pencarian.
- Tidak mengubah struktur URL publik yang sudah terindeks kecuali ditemukan masalah kritis.

## Temuan Audit Awal

- Metadata dasar dan schema `EducationalOrganization` sudah tersedia.
- `sitemap.xml` belum tersedia pada situs live.
- `robots.txt` belum mencantumkan lokasi sitemap.
- Nilai `og:image` pada beranda masih kosong.
- Canonical memakai URL halaman saat ini, tetapi normalisasi host dan query perlu dipastikan.
- Tailwind dimuat melalui CDN dan aset eksternal perlu ditinjau untuk Core Web Vitals.
- Beberapa halaman sudah muncul di hasil pencarian, sehingga perubahan harus menjaga URL yang ada.

## Pemetaan Halaman dan Keyword

| Halaman | Fokus utama |
| --- | --- |
| Beranda | SMK Pertanian & IT berbasis Pesantren Tahfidz di Jonggol, Bogor |
| Profil | Pondok Pesantren Tahfidz Al-Falah Jonggol |
| Program | Program unggulan SMK boarding school dan pesantren tahfidz |
| Detail program Tahfidz | Program Tahfidz Al-Qur'an di Jonggol |
| Detail program Pertanian | SMK Pertanian/Agribisnis berbasis pesantren di Bogor |
| Detail program IT | SMK IT berbasis pesantren tahfidz di Jonggol |
| PPDB | PPDB pesantren tahfidz dan SMK boarding school Jonggol |
| Fasilitas | Fasilitas pesantren dan SMK boarding school di Jonggol |
| Berita | Berita, kegiatan, dan prestasi PPT Al-Falah |
| Kontak | Alamat dan kontak PPT Al-Falah Jonggol Bogor |

Keyword digunakan secara alami pada judul, deskripsi, heading, dan isi yang relevan. Tidak dilakukan pengulangan keyword secara berlebihan.

## SEO Teknis

### Metadata

- Layout menyediakan title, description, canonical, Open Graph, dan Twitter Card dengan fallback yang valid.
- Halaman statis memiliki metadata unik.
- Artikel dan program memakai judul, ringkasan/deskripsi, serta gambar masing-masing.
- Gambar sosial default memakai aset lokal lembaga ketika halaman tidak memiliki gambar.
- Halaman hasil pencarian internal diberi `noindex,follow`; filter kategori tetap memakai canonical yang bersih.

### Sitemap dan Robots

- Laravel menghasilkan `/sitemap.xml` tanpa dependency baru.
- Sitemap memuat halaman statis, program aktif, artikel terbit, agenda, pengumuman, dan halaman CMS yang layak diindeks.
- Entri dinamis menyertakan `lastmod` jika datanya tersedia.
- `robots.txt` mengizinkan halaman publik dan mencantumkan URL sitemap absolut.

### Canonical dan Host

- Canonical tidak membawa parameter pencarian atau pelacakan yang tidak diperlukan.
- Host produksi memakai satu bentuk utama: `https://pptalfalah.com`.
- Versi `www` dan HTTP diarahkan permanen ke host utama melalui konfigurasi hosting jika belum aktif.

## Structured Data

- Layout utama memakai `EducationalOrganization` dan `School` dengan nama, URL, logo, alamat, telepon, dan email yang konsisten.
- Halaman artikel memakai schema `Article` dengan headline, image, tanggal terbit/perubahan, author, dan publisher.
- Halaman detail memakai `BreadcrumbList` sesuai navigasi yang terlihat.
- Schema PPDB/FAQ hanya ditambahkan jika informasi yang sama benar-benar terlihat pada halaman.
- JSON-LD dibentuk melalui encoder JSON agar karakter dan data dinamis tetap valid.

## SEO Lokal dan Konversi

- Nama lembaga, alamat, nomor telepon, dan wilayah Jonggol–Bogor konsisten di seluruh situs.
- Beranda, profil, PPDB, fasilitas, dan kontak menjelaskan lokasi serta keunggulan Pertanian, IT, dan Tahfidz secara alami.
- CTA utama menuju PPDB; CTA sekunder menuju WhatsApp/kontak.
- Tautan internal menghubungkan beranda, tiga program utama, fasilitas, PPDB, berita, dan kontak.
- Google Business Profile menjadi pekerjaan operasional terpisah karena membutuhkan akses akun pemilik.

## Performa

- Prioritaskan gambar hero/LCP lokal yang berukuran tepat dan memiliki dimensi eksplisit.
- Terapkan lazy loading hanya pada gambar di bawah fold.
- Kurangi ketergantungan runtime Tailwind CDN dengan build Vite yang sudah tersedia, selama hasil visual tetap sama.
- Hindari penambahan library SEO baru; gunakan Blade, route Laravel, dan encoder JSON bawaan.

## Validasi

- Jalankan test route sitemap dan metadata halaman utama/dinamis.
- Pastikan XML sitemap valid dan hanya memuat URL publik berstatus sukses.
- Periksa canonical, robots, Open Graph, Twitter Card, dan JSON-LD pada halaman utama serta detail.
- Jalankan test suite Laravel dan build frontend yang relevan.
- Setelah deployment, buka `/robots.txt` dan `/sitemap.xml`, lalu kirim sitemap melalui Google Search Console.

## Kriteria Selesai

- Semua halaman prioritas memiliki title dan description yang unik serta relevan.
- Open Graph tidak lagi menghasilkan gambar kosong.
- Sitemap dan robots dapat diakses publik.
- Schema sesuai dengan konten yang terlihat dan lolos parsing JSON.
- Halaman PPDB dan program utama mendapat tautan internal yang jelas.
- Tidak ada URL publik lama yang rusak akibat perubahan.
