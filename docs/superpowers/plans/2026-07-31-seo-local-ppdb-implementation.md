# SEO Lokal dan PPDB Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Memaksimalkan SEO teknis, lokal, dan konversi PPDB `pptalfalah.com` untuk positioning “SMK Pertanian & IT berbasis Pesantren Tahfidz di Jonggol, Bogor”.

**Architecture:** Pertahankan route dan struktur Laravel yang sudah terindeks. Metadata tetap didefinisikan melalui Blade sections, layout menyediakan fallback dan schema organisasi yang aman, sitemap dihasilkan oleh controller invokable dan Blade XML, sedangkan pipeline aset memakai Tailwind/Vite yang sudah terpasang.

**Tech Stack:** PHP 8.2+, Laravel 12, Blade, PHPUnit 11, Tailwind CSS 4, Vite 7.

## Global Constraints

- Tidak menambah dependency baru.
- Tidak mengubah URL publik yang sudah ada.
- Tidak membuat halaman atau artikel tipis.
- Gunakan `https://pptalfalah.com` sebagai host canonical produksi.
- Pertahankan desain visual yang ada.
- Jangan commit atau push kecuali diminta pengguna.
- Abaikan file tidak terkait `database_utf8.sql` yang sudah untracked.

---

### Task 1: Fondasi Metadata Global

**Files:**
- Create: `tests/Feature/SeoTest.php`
- Modify: `resources/views/layouts/app.blade.php:7`

**Interfaces:**
- Consumes: Blade sections `meta_title`, `meta_description`, `og_image`, `og_type`, `canonical`, dan `robots`.
- Produces: Head HTML dengan canonical bersih, robots, Open Graph, Twitter Card, dan JSON-LD organisasi.

- [ ] **Step 1: Tulis test metadata global yang gagal**

Tambahkan test beranda yang mengatur `app.url` ke `https://pptalfalah.com`, lalu memastikan title dan description tidak kosong, `og:image` memakai fallback lokal, Twitter Card tersedia, canonical tepat, serta JSON-LD mengandung `EducationalOrganization` dan `School`.

- [ ] **Step 2: Jalankan test untuk memastikan gagal**

Run: `php artisan test tests/Feature/SeoTest.php --filter=homepage_has_complete_seo_metadata`

Expected: FAIL karena Twitter Card, robots, fallback gambar, atau schema gabungan belum tersedia.

- [ ] **Step 3: Implementasikan head global minimal**

Di layout, hitung nilai SEO melalui `yieldContent`, gunakan `assets/LOGO1.jpeg` sebagai fallback gambar sosial, tambahkan Open Graph URL/site name, Twitter Card, robots, dan canonical yang membuang parameter tracking namun mempertahankan pagination. Bentuk schema dengan array PHP dan `json_encode` ber-flag aman; hapus `meta keywords` yang tidak digunakan mesin pencari.

- [ ] **Step 4: Jalankan test metadata global**

Run: `php artisan test tests/Feature/SeoTest.php --filter=homepage_has_complete_seo_metadata`

Expected: PASS.

---

### Task 2: Metadata Halaman dan Structured Data

**Files:**
- Modify: `tests/Feature/SeoTest.php`
- Modify: `resources/views/home.blade.php:3`
- Modify: `resources/views/pages/show.blade.php:3`
- Modify: `resources/views/pages/programs/index.blade.php:1`
- Modify: `resources/views/pages/programs/show.blade.php:3`
- Modify: `resources/views/pages/ppdb.blade.php:1`
- Modify: `resources/views/pages/gallery.blade.php:1`
- Modify: `resources/views/pages/articles/index.blade.php:1`
- Modify: `resources/views/pages/articles/show.blade.php:1`
- Modify: `resources/views/pages/agendas/index.blade.php:1`
- Modify: `resources/views/pages/agendas/show.blade.php:1`
- Modify: `resources/views/pages/announcements/index.blade.php:1`
- Modify: `resources/views/pages/announcements/show.blade.php:1`
- Modify: `resources/views/pages/facilities/index.blade.php:3`
- Modify: `resources/views/pages/contact.blade.php:3`

**Interfaces:**
- Consumes: Model fields `meta_title`, `meta_description`, `og_image`, `featured_image`, `description`, `excerpt`, timestamps, author, dan slug.
- Produces: Metadata unik per halaman, `Article` schema untuk berita, `BreadcrumbList` pada halaman detail, dan `noindex,follow` untuk pencarian/filter internal.

- [ ] **Step 1: Tulis test halaman dinamis yang gagal**

Buat artikel terbit dan program aktif secara langsung di test database. Pastikan detail artikel memakai metadata/model image, menghasilkan schema `Article` dan breadcrumb; detail program memakai title/description/image; pencarian berita memakai `noindex,follow` dan canonical daftar berita.

- [ ] **Step 2: Jalankan test untuk memastikan gagal**

Run: `php artisan test tests/Feature/SeoTest.php --filter='article|program|search'`

Expected: FAIL karena sections dan schema belum tersedia.

- [ ] **Step 3: Tambahkan metadata unik ke view prioritas**

Gunakan copy lokal yang alami untuk beranda, program, PPDB, fasilitas, berita, agenda, pengumuman, galeri, dan kontak. Untuk model dinamis, gunakan field SEO jika ada lalu fallback ke title/description/excerpt; bentuk URL gambar absolut melalui `asset()`.

- [ ] **Step 4: Tambahkan schema detail minimal**

Gunakan `@push('structured_data')` untuk schema `Article` dan `BreadcrumbList`, dengan JSON encoding aman yang sama seperti layout. Jangan menambahkan FAQ schema tanpa FAQ terlihat.

- [ ] **Step 5: Jalankan test halaman dinamis**

Run: `php artisan test tests/Feature/SeoTest.php --filter='article|program|search'`

Expected: PASS.

---

### Task 3: Sitemap Dinamis dan Robots

**Files:**
- Modify: `tests/Feature/SeoTest.php`
- Create: `app/Http/Controllers/SitemapController.php`
- Create: `resources/views/sitemap.blade.php`
- Modify: `routes/web.php:1`
- Modify: `public/robots.txt:1`

**Interfaces:**
- Produces: `SitemapController::__invoke(): Response`, route bernama `sitemap`, dan XML `urlset` pada `/sitemap.xml`.
- Sitemap sources: route statis publik, `Page::published()`, `Program::active()`, `Article::published()`, `Agenda::published()`, dan `Announcement::published()`.

- [ ] **Step 1: Tulis test sitemap dan robots yang gagal**

Buat satu record publik dan satu record tersembunyi untuk tipe dinamis yang relevan. Pastikan `/sitemap.xml` berstatus 200, bertipe XML, memuat URL publik, tidak memuat draft/nonaktif, dan `public/robots.txt` menunjuk ke sitemap absolut.

- [ ] **Step 2: Jalankan test untuk memastikan gagal**

Run: `php artisan test tests/Feature/SeoTest.php --filter='sitemap|robots'`

Expected: FAIL karena route sitemap belum ada dan robots belum menunjuk sitemap.

- [ ] **Step 3: Implementasikan controller dan XML view**

Bangun satu collection item `{loc,lastmod}` tanpa package baru. Gunakan route bernama untuk halaman statis; canonical profil memakai `/profil`, sedangkan halaman CMS lain memakai `/halaman/{slug}`. Escape XML melalui output Blade biasa dan format tanggal sebagai Atom.

- [ ] **Step 4: Daftarkan route dan perbarui robots**

Tambahkan `Route::get('/sitemap.xml', SitemapController::class)->name('sitemap')`. Isi robots dengan `Allow: /`, blokir `/admin`, dan tambahkan `Sitemap: https://pptalfalah.com/sitemap.xml`.

- [ ] **Step 5: Jalankan test sitemap dan robots**

Run: `php artisan test tests/Feature/SeoTest.php --filter='sitemap|robots'`

Expected: PASS.

---

### Task 4: SEO Lokal dan Internal Linking

**Files:**
- Modify: `tests/Feature/SeoTest.php`
- Modify: `resources/views/home.blade.php:49`
- Modify: `resources/views/pages/programs/index.blade.php:1`
- Modify: `resources/views/pages/ppdb.blade.php:18`
- Modify: `resources/views/pages/facilities/index.blade.php:56`
- Modify: `resources/views/pages/contact.blade.php:20`

**Interfaces:**
- Produces: Copy visible yang menyebut Pertanian, IT, Tahfidz, Jonggol, dan Bogor secara alami serta CTA/link internal menuju program, PPDB, fasilitas, dan kontak.

- [ ] **Step 1: Tulis test positioning lokal yang gagal**

Pastikan beranda memuat positioning utama dan tautan ke PPDB/program, halaman PPDB menyebut Jonggol/Bogor serta tiga pilar, dan kontak/fasilitas memiliki deskripsi lokal yang relevan.

- [ ] **Step 2: Jalankan test untuk memastikan gagal**

Run: `php artisan test tests/Feature/SeoTest.php --filter=priority_pages_explain_local_positioning`

Expected: FAIL karena copy belum lengkap atau tersebar tidak konsisten.

- [ ] **Step 3: Perbarui copy dan tautan internal**

Ubah hanya heading/deskripsi fallback dan CTA yang relevan. Jangan memaksa keyword pada data CMS yang sudah dikelola admin dan jangan menambah section baru jika tautan yang ada dapat diperjelas.

- [ ] **Step 4: Jalankan test positioning lokal**

Run: `php artisan test tests/Feature/SeoTest.php --filter=priority_pages_explain_local_positioning`

Expected: PASS.

---

### Task 5: Pipeline CSS Produksi

**Files:**
- Modify: `tests/Feature/SeoTest.php`
- Modify: `resources/css/app.css:1`
- Modify: `resources/views/layouts/app.blade.php:48`

**Interfaces:**
- Consumes: Vite manifest dan Tailwind CSS 4 yang sudah terpasang.
- Produces: CSS build lokal dengan token warna/font lama dan fallback gaya `.prose`; layout tidak lagi memuat runtime Tailwind CDN.

- [ ] **Step 1: Tulis test aset yang gagal**

Pastikan HTML beranda memuat aset Vite dan tidak memuat `cdn.tailwindcss.com` maupun konfigurasi Tailwind inline.

- [ ] **Step 2: Jalankan test untuk memastikan gagal**

Run: `php artisan test tests/Feature/SeoTest.php --filter=site_uses_compiled_tailwind_assets`

Expected: FAIL karena layout masih memakai CDN.

- [ ] **Step 3: Pindahkan theme ke CSS lokal**

Salin token warna dan font yang benar-benar digunakan ke `@theme` Tailwind 4. Tambahkan CSS `.prose` minimal untuk heading, paragraf, list, link, dan gambar karena plugin typography tidak terpasang.

- [ ] **Step 4: Aktifkan aset Vite dan hapus CDN**

Tambahkan `@vite(['resources/css/app.css', 'resources/js/app.js'])`, pertahankan Google Fonts/Material Symbols, tambahkan preconnect, lalu hapus script CDN dan konfigurasi inline.

- [ ] **Step 5: Jalankan test aset dan build**

Run: `php artisan test tests/Feature/SeoTest.php --filter=site_uses_compiled_tailwind_assets`

Run: `npm run build`

Expected: test PASS dan build exit 0 tanpa warning/error yang relevan.

---

### Task 6: Verifikasi Menyeluruh

**Files:**
- Verify only; jangan ubah file tidak terkait.

- [ ] **Step 1: Jalankan formatter pada file PHP yang berubah**

Run: `vendor/bin/pint --dirty`

Expected: exit 0.

- [ ] **Step 2: Jalankan seluruh test**

Run: `php artisan test`

Expected: seluruh test PASS.

- [ ] **Step 3: Jalankan build produksi ulang**

Run: `npm run build`

Expected: exit 0.

- [ ] **Step 4: Periksa route dan diff**

Run: `php artisan route:list --path=sitemap`

Run: `git diff --check`

Run: `git status --short`

Expected: route sitemap terdaftar, tidak ada whitespace error, dan hanya file SEO/plan yang berubah selain `database_utf8.sql` milik pengguna.

- [ ] **Step 5: Catat langkah deployment**

Setelah push/deploy oleh pengguna: jalankan `php artisan optimize:clear && php artisan optimize`, buka `/sitemap.xml` dan `/robots.txt`, lalu submit `https://pptalfalah.com/sitemap.xml` di Google Search Console.
