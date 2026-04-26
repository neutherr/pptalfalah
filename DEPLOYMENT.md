# 🚀 Panduan Deploy & Update — PPT Al-Falah

## 📋 Informasi Server

| Info           | Detail                                 |
| -------------- | -------------------------------------- |
| **Hosting**    | Hostinger Shared Hosting               |
| **hPanel**     | https://hpanel.hostinger.com/          |
| **Domain**     | pptalfalah.com                         |
| **SSH User**   | u818194417                             |
| **Server**     | id-dci-web1786                         |
| **Directory**  | `~/domains/pptalfalah.com/public_html` |
| **Repository** | https://github.com/neutherr/pptalfalah |

---

## 🔄 Alur Update Project (Rutin)

```
Edit kode di lokal → Push ke GitHub → SSH ke server → Pull → Migrate → Optimize
```

---

## 💻 Step 1 — Push dari Komputer Lokal

Lakukan ini setiap selesai mengerjakan fitur/perubahan:

```bash
# Di terminal lokal (PowerShell / Git Bash)
cd C:\xampp\htdocs\pptalfalah

# Cek file yang berubah
git status

# Tambahkan semua perubahan
git add .

# Commit dengan pesan yang jelas
git commit -m "feat: deskripsi perubahan yang dilakukan"

# Push ke GitHub
git push origin main
```

> **📝 Tips pesan commit yang baik:**
>
> - `feat: tambah halaman galeri`
> - `fix: perbaiki bug upload foto`
> - `update: perbarui konten hero section`

---

## 🖥️ Step 2 — Login SSH ke Hostinger

### Via Terminal / PowerShell

```bash
ssh u818194417@pptalfalah.com
ssh -p 65002 u818194417@46.202.138.100
```

### Via hPanel

1. Login ke https://hpanel.hostinger.com/
2. Pilih domain **pptalfalah.com**
3. Klik **SSH Access** di menu Advanced
4. Klik **Launch SSH Terminal**

---

## ⬇️ Step 3 — Pull & Update di Server

Jalankan perintah berikut setelah berhasil SSH:

```bash
# Masuk ke direktori project
cd ~/domains/pptalfalah.com/public_html

# Pull perubahan terbaru dari GitHub
git pull origin main

# Jalankan migration baru (jika ada perubahan database)
php artisan migrate --force

# Clear cache lama & buat cache baru
php artisan optimize:clear && php artisan optimize
```

> ✅ Selesai! Website otomatis updated.

---

## 📦 Jika Ada Perubahan Composer (package baru)

Jika kamu menambahkan package baru via `composer require`, tambahkan langkah ini:

```bash
cd ~/domains/pptalfalah.com/public_html

git pull origin main

# Install/update dependencies
composer install --no-dev --optimize-autoloader

php artisan migrate --force
php artisan optimize:clear && php artisan optimize
```

---

## 🖼️ Jika Ada Perubahan Asset (CSS/JS)

Jika kamu mengubah file CSS, JS, atau menjalankan `npm run build` secara lokal:

```bash
# Di lokal — build dulu, lalu commit hasilnya
npm run build
git add .
git commit -m "build: update compiled assets"
git push origin main
```

> **Catatan:** File hasil build (`public/build/`) harus ikut di-commit dan push.

---

## 🗄️ Jika Ada Perubahan Database Besar

Jika ada perubahan schema yang signifikan dan butuh seeder ulang:

```bash
cd ~/domains/pptalfalah.com/public_html

git pull origin main
php artisan migrate --force

# Jalankan seeder spesifik (hati-hati, bisa overwrite data!)
php artisan db:seed --class=NamaSeederYangDiinginkan --force

php artisan optimize:clear && php artisan optimize
```

> ⚠️ **JANGAN jalankan `db:seed` tanpa `--class` di production**, karena akan mereset semua data!

---

## 🔑 GitHub Authentication

Saat pertama kali clone atau jika token kedaluwarsa, gunakan Personal Access Token (PAT):

```bash
git clone https://neutherr:TOKEN@github.com/neutherr/pptalfalah.git public_html
```

### Cara Buat PAT Baru:

1. Login ke https://github.com
2. **Settings** → **Developer settings** → **Personal access tokens** → **Tokens (classic)**
3. Klik **Generate new token (classic)**
4. Centang scope **`repo`**
5. Klik **Generate token** → Copy token-nya

> ⚠️ **Segera hapus/revoke token lama** setelah membuat yang baru!

---

## 🆘 Troubleshooting

### ❌ 403 Forbidden

Website tidak bisa diakses. Pastikan `.htaccess` di root project ada:

```bash
cat ~/domains/pptalfalah.com/public_html/.htaccess
```

Isinya harus:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

Jika tidak ada, buat dengan:

```bash
cat > ~/domains/pptalfalah.com/public_html/.htaccess << 'EOF'
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
EOF
```

---

### ❌ Foto/File Upload Hilang

Terjadi karena file upload tidak ikut di Git. Copy dari backup:

```bash
cp -r ~/domains/pptalfalah.com/public_html_backup/storage/app/public/* \
      ~/domains/pptalfalah.com/public_html/storage/app/public/
```

---

### ❌ `storage:link` Error (`exec()` disabled)

Buat symlink manual:

```bash
rm -rf ~/domains/pptalfalah.com/public_html/public/storage
ln -s ~/domains/pptalfalah.com/public_html/storage/app/public \
      ~/domains/pptalfalah.com/public_html/public/storage
```

---

### ❌ Error Setelah Pull (class not found, dll)

```bash
cd ~/domains/pptalfalah.com/public_html
php artisan optimize:clear
composer dump-autoload
php artisan optimize
```

---

## 📁 Struktur Direktori Server

```
~/domains/pptalfalah.com/
├── public_html/              ← Root project Laravel (git repo)
│   ├── .htaccess             ← Redirect ke /public (JANGAN DIHAPUS!)
│   ├── public/               ← Entry point web server
│   │   ├── index.php
│   │   └── storage -> ../storage/app/public  (symlink)
│   ├── storage/
│   │   └── app/public/       ← File upload dari CMS disimpan di sini
│   └── ...
└── public_html_backup/       ← Backup sebelum deploy pertama
```

---

## ✅ Checklist Update Rutin

- [ ] Edit & test kode di lokal (XAMPP)
- [ ] `git add . && git commit -m "..."` di lokal
- [ ] `git push origin main` di lokal
- [ ] SSH ke server Hostinger
- [ ] `cd ~/domains/pptalfalah.com/public_html`
- [ ] `git pull origin main`
- [ ] `php artisan migrate --force` (jika ada migration baru)
- [ ] `php artisan optimize:clear && php artisan optimize`
- [ ] Cek website di browser ✅
