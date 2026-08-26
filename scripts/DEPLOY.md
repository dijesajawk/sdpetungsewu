# Deploy Guide - Web Edukasi

## Yang Dibutuhkan
- **Hostinger Business/Cloud Hosting** (PHP 8.3+)
- **Node.js** di local (untuk build assets)
- **Composer** di server Hostinger (biasanya sudah ada)

---

## STEP 1: Konfigurasi .env

Edit `scripts/.env.production` — isi data dari Hostinger:

```
APP_URL=https://domain-kamu.com
DB_DATABASE=uXXXXXXX_edukasi
DB_USERNAME=uXXXXXXX
DB_PASSWORD=password-kamu
SESSION_DOMAIN=.domain-kamu.com
```

---

## STEP 2: Jalankan Script Local

Buka **PowerShell** di folder project:

```powershell
.\scripts\deploy-local.ps1
```

Script ini akan:
1. Bersihkan folder deploy lama
2. Copy file project (tanpa node_modules, vendor, tests)
3. Build assets (npm run build)
4. Compress jadi `web-edukasi-deploy.zip`

---

## STEP 3: Upload ke Hostinger

1. Login **hPanel** → **File Manager**
2. Buka `public_html/`
3. Upload `web-edukasi-deploy.zip`
4. Klik kanan → **Extract** di `public_html/`

---

## STEP 4: Setup di Hostinger

### a) Database
1. hPanel → **Databases** → **MySQL Databases**
2. Buat database baru (nama, username, password)
3. Catat datanya

### b) PHP Version
1. hPanel → **PHP Settings**
2. Pilih **PHP 8.3**

### c) Document Root
1. hPanel → **Websites** → **Manage** → **Domain**
2. Set Document Root ke `public_html/public`

### d) SSL
1. hPanel → **SSL/TLS**
2. Aktifkan Let's Encrypt

---

## STEP 5: Jalankan Script Server

1. Buka **SSH Terminal** di hPanel
2. Jalankan:

```bash
cd ~/public_html
bash deploy.sh
```

Script ini akan:
1. Cek PHP version
2. Install composer dependencies
3. Setup .env & generate APP_KEY
4. Set permissions
5. Jalankan migration
6. Clear & optimize cache

---

## STEP 6: Test!

Buka browser → akses domain kamu → login → test semua fitur.

---

## Troubleshooting

| Masalah | Solusi |
|---------|--------|
| Error 500 | Set `APP_DEBUG=true` di .env, cek error di browser |
| Database error | Cek DB_HOST, DB_USERNAME, DB_PASSWORD di .env |
| Assets 404 | Pastikan `public/build/` ada dari hasil npm run build |
| Routing error | Pastikan .htaccess di public/ ada dan Document Root benar |
| Permission denied | Jalankan `chmod -R 775 storage bootstrap/cache` |

---

## File Structure di Server

```
~/public_html/
├── public/         ← Document Root
│   ├── build/      ← Assets (CSS/JS)
│   ├── index.php
│   └── .htaccess
├── app/
├── bootstrap/
├── config/
├── database/
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env
├── artisan
└── composer.json
```
