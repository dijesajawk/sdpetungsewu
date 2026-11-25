# BAB II SPESIFIKASI APLIKASI

## 2.1 Spesifikasi Perangkat Keras

Spesifikasi minimum berikut belum ditemukan di kode dan perlu ditetapkan oleh instansi.

| Kebutuhan | Spesifikasi minimum | Keterangan |
|---|---|---|
| Perangkat pengguna | [ISI MANUAL: contoh — laptop/PC, tablet, atau HP Android dengan kamera untuk Scan Soal] | Aplikasi sudah responsif untuk desktop, tablet, dan HP |
| Prosesor / RAM | [ISI MANUAL: contoh — RAM minimal 4 GB untuk OCR di browser] | Proses OCR Tesseract.js berjalan di browser pengguna |
| Kamera/pemindai | [ISI MANUAL: kamera HP atau webcam untuk foto soal cetak] | Diperlukan untuk fitur **Scan Soal** |
| Proyektor/layar kelas | [ISI MANUAL: kebutuhan opsional untuk menampilkan kuis di kelas] | Sesuai panduan `PANDUAN_GURU_QUICK.md` |
| Server/hosting | [ISI MANUAL: spesifikasi server produksi, CPU, RAM, dan penyimpanan] | Tidak ditemukan di kode |

## 2.2 Spesifikasi Perangkat Lunak

Tabel 2.1 — Spesifikasi perangkat lunak berdasarkan kode.

| Komponen | Hasil pembacaan kode | Keterangan |
|---|---|---|
| Jenis aplikasi | Aplikasi web | Diakses melalui peramban, rute didefinisikan di `routes/web.php` |
| Framework backend | Laravel (pada `composer.json` tertulis `laravel/framework: ^13.8`) | Dokumentasi lama menyebut Laravel 11; yang berlaku untuk manual ini adalah versi di `composer.json` |
| Bahasa pemrograman | PHP `^8.3` (`composer.json`) | Dokumentasi lama menyebut minimal PHP 8.1/8.2; ikuti `composer.json` |
| Database | MySQL (`DB_CONNECTION=mysql` pada `.env.example`) | Tabel: `users`, `kelas`, `kategori`, `soal`, `soal_sessions`, `soal_session_items`, `jawaban`, ditambah tabel bawaan Laravel (`cache`, `jobs`, `sessions`, `password_reset_tokens`) |
| Frontend | Blade + Tailwind CSS + Vite (`package.json`: `tailwindcss ^4.0.0`, `vite ^8.0.0`) | Berkas tema di `public/assets/css/theme.css` dan `public/assets/js/theme.js` |
| Pustaka tambahan | `smalot/pdfparser ^2.12` untuk PDF; Tesseract.js v5 dari CDN untuk OCR di halaman review scan; `pdftotext` dan `catdoc` sebagai fallback opsional di server (`GuruController.php`) | Tidak ada layanan AI generate soal otomatis di kode saat ini |
| Peramban yang didukung | [ISI MANUAL: contoh — Chrome/Edge versi terbaru] | Tidak dinyatakan eksplisit di kode; OCR dan Tailwind membutuhkan peramban modern |
| Sistem operasi server | [ISI MANUAL: contoh — Windows + XAMPP/Laragon atau Linux + Nginx/Apache] | Panduan lama memakai `php artisan serve`, Laragon, dan XAMPP |

Akun bawaan dari seeder (`database/seeders/`):

| Peran | Email | Password | Status awal |
|---|---|---|---|
| Admin | `admin@edukasi.local` | `password123` | `verified` |
| Guru contoh | `guru@edukasi.local` | `password123` | `pending` |

Data awal lain: Kelas 1 SD sampai Kelas 6 SD (`KelasSeeder.php`) dan kategori `mudah`, `normal`, `sulit` (`KategoriSeeder.php`).

## 2.3 Spesifikasi Pengguna

| Pengguna | Pengetahuan minimum | Perangkat yang disarankan |
|---|---|---|
| Guru | Dapat mengoperasikan peramban, mengisi formulir, mengunggah foto/berkas | Laptop/PC; HP berkamera untuk Scan Soal |
| Admin | Dapat memeriksa identitas pendaftar guru | Laptop/PC |
| Murid tanpa login | Dapat membaca soal dan mengeklik pilihan A–D | HP/tablet/laptop kelas atau perangkat bersama |
| Siswa dengan akun login | Dapat login dengan email dan password | HP/tablet/laptop |

## 2.4 Hak Akses Pengguna (Tabel Role x Fitur)

Tabel 2.2 — Matriks peran dan fitur berdasarkan `routes/web.php`, middleware, dan controller.

| Fitur / Menu | Pengunjung | Guru `verified` | Admin | Murid tanpa login | Siswa login |
|---|---|---|---|---|---|
| Halaman utama `/` | Ya | Ya | Ya | Ya | Ya |
| **Login** (`/login`) | Ya | Ya | Ya | Tidak relevan | Ya |
| **Daftar sebagai Guru** (`/register`) | Ya | Ya (membuat akun guru baru) | Tidak ada menu khusus | Tidak relevan | Tidak ada di kode |
| Dashboard Admin (`/admin/dashboard`), **✓ Verifikasi**, **✕ Tolak** | Tidak | Tidak (dialihkan ke login) | Ya | Tidak | Tidak |
| Dashboard Guru (`/guru/dashboard`), **＋ Tambah Soal**, **Quiziz — Atur & Mulai**, **Jalankan Soal Langsung**, **Lihat Semua Soal Saya →** | Tidak | Ya | Tidak | Tidak | Tidak |
| **Daftar Soal Saya** (`/guru/soal`), **Filter**, **Reset**, **Edit**, **Hapus**, **▶ Jalankan** | Tidak | Ya, hanya soal milik sendiri | Tidak | Tidak | Tidak |
| **Buat Soal Baru** (`/guru/soal/create`), **Simpan Soal**, **Batal** | Tidak | Ya | Tidak | Tidak | Tidak |
| **Scan Soal** (`/guru/soal/scan/upload`), **Proses & Scan**, **💾 Simpan Semua Soal**, **Scan Ulang** | Tidak | Ya | Tidak | Tidak | Tidak |
| **Upload File Soal** (`/guru/soal/upload/file`), **📤 Upload & Proses**, **💾 Simpan Semua Soal**, **Upload Ulang** | Tidak | Ya | Tidak | Tidak | Tidak |
| **▶ Mulai Soal** (`/guru/quiz/start`), **▶ Mulai Session (Buat Session)**, **🔎 Tampilkan Langsung (Tanpa Session)** | Tidak | Ya | Tidak | Tidak | Tidak |
| Halaman sesi guru (`/guru/quiz/{session}/display`), **📋 Salin Link**, **📊 Lihat Hasil Jawaban Murid (Real-time)**, **✕ Tutup Session** | Tidak | Ya, hanya sesi milik sendiri | Tidak | Tidak | Tidak |
| Immediate guru (`/guru/quiz/immediate`), **Jawab**, **Acak Ulang**, **Keluar** | Tidak | Ya | Tidak | Tidak | Tidak |
| Kuis publik (`/quiz/{sessionCode}/name`, `/quiz/{sessionCode}`, `/quiz/{sessionCode}/result`) | Ya, dengan kode/link valid dan sesi aktif | Tidak ada menu khusus | Tidak ada menu khusus | Ya | Tidak relevan |
| Dashboard Siswa (`/student/dashboard`), Daftar Soal, Kuis, Hasil | Tidak | Tidak | Tidak | Tidak | Ya (hanya rute `auth`, tanpa middleware peran khusus di kode) |
| Logout (tombol ikon keluar di navbar) | Tidak tampil | Ya | Ya | Tidak ada akun | Ya |
| Cetak/export laporan (PDF/Excel), kelola profil, reset password | Tidak ditemukan di kode | Tidak ditemukan di kode | Tidak ditemukan di kode | Tidak ditemukan di kode | Tidak ditemukan di kode |

Catatan pembacaan kode:

1. `AdminMiddleware.php` hanya memeriksa `role === 'admin'`.
2. `GuruMiddleware.php` memeriksa `role === 'guru'` dan `status === 'verified'`.
3. Rute `/student/*` hanya memakai middleware `auth`, tanpa pemeriksaan peran tambahan.
4. `README.md` menyebut "Export soal" dan "Generate soal otomatis (AI)", tetapi tidak ditemukan rute, controller, maupun tombol export/AI di kode saat ini. Yang tersedia adalah ekstraksi soal dari foto dan berkas.
