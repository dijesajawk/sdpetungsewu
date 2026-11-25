# USER MANUAL — Web Edukasi (SD Negeri 2 Petungsewu)

> Dokumen gabungan. Sumber per bab tersimpan di folder yang sama: `00-cover.md` s.d. `08-lampiran.md`. Screenshot ditempatkan di `screenshots/`.

---

# USER MANUAL

## Web Edukasi — Platform Pembelajaran Interaktif

| Keterangan | Isi |
|---|---|
| Nama aplikasi | Web Edukasi — SD Negeri 2 Petungsewu |
| Jenis aplikasi | Aplikasi web (diakses melalui peramban) |
| Versi dokumen | 1.0 |
| Tahun | [ISI MANUAL: tahun terbit resmi, misalnya 2026] |
| Pengembang / Instansi | [ISI MANUAL: nama pengembang dan SD Negeri 2 Petungsewu, Wagir, Kabupaten Malang — peran masing-masing] |
| Logo | Menggunakan berkas `public/images/logo-sekolah.jpg` sebagai logo sekolah di halaman aplikasi |
| Alamat akses | [ISI MANUAL: alamat resmi aplikasi, misalnya https://... atau http://localhost:8000 untuk lokal] |
| Kontak support | [ISI MANUAL: nama, telepon/WA, dan email admin/support] |

---

# KATA PENGANTAR

Aplikasi Web Edukasi — SD Negeri 2 Petungsewu adalah aplikasi web untuk membantu guru membuat dan mengelola soal, menjalankan kuis di kelas, serta membantu murid menjawab soal dan melihat hasil secara langsung.

User manual ini disusun dalam Bahasa Indonesia yang baku dan sederhana agar mudah diikuti oleh pengguna awam, termasuk guru, admin, murid, dan siswa yang memiliki akun.

Ruang lingkup manual ini mencakup:

1. Pengenalan aplikasi, tujuan, dan manfaat.
2. Spesifikasi perangkat, perangkat lunak, dan hak akses pengguna.
3. Panduan penggunaan langkah demi langkah untuk setiap peran.
4. Troubleshooting berdasarkan validasi dan pesan error yang benar-benar ada di kode.
5. Lampiran contoh output dan daftar screenshot yang perlu dilengkapi.

Semua fitur, menu, field formulir, dan pesan yang dikutip dalam manual ini didasarkan pada hasil pembacaan codebase pada tanggal 8 Oktober 2026. Bagian yang tidak ditemukan di kode ditandai dengan `[ISI MANUAL: ...]`.

[ISI MANUAL: kata sambutan resmi dari kepala sekolah atau pimpinan instansi, maksimal 1 halaman]

---

# DAFTAR ISI

- Cover
- Kata Pengantar
- Daftar Isi
- BAB I PENDAHULUAN (1.1 Latar Belakang, 1.2 Tujuan, 1.3 Manfaat, 1.4 Pengguna Aplikasi)
- BAB II SPESIFIKASI APLIKASI (2.1 Perangkat Keras, 2.2 Perangkat Lunak, 2.3 Spesifikasi Pengguna, 2.4 Hak Akses)
- BAB III PANDUAN PENGGUNAAN (3.1 Membuka Aplikasi, 3.2 Guru, 3.3 Admin, 3.4 Murid Tanpa Login, 3.5 Siswa Login, 3.6 Logout)
- BAB IV TROUBLESHOOTING
- BAB V PENUTUP
- LAMPIRAN (A. Screenshot, B. Contoh Output, C. Kontak Support)

Penomoran gambar: Gambar X.Y. Penomoran tabel: Tabel X.Y.

---

# BAB I PENDAHULUAN

## 1.1 Latar Belakang

Web Edukasi adalah aplikasi web berbasis Laravel yang digunakan di SD Negeri 2 Petungsewu, Wagir, Kabupaten Malang. Berdasarkan `README.md`, `PANDUAN_LENGKAP.md`, dan implementasi di kode, aplikasi ini dibuat untuk membantu guru membuat soal dengan lebih cepat dan efisien, menghemat waktu penyusunan soal, dan mengurangi kerja manual.

Wujud implementasi di kode (`routes/web.php`, `GuruController.php`, `QuizController.php`):

- Bank soal per guru dengan tingkat kesulitan dan mata pelajaran.
- Input soal manual satu per satu.
- Import soal dari foto hasil pindaian (Scan Soal / OCR di browser dengan Tesseract.js).
- Import soal dari berkas PDF, DOC, dan DOCX.
- Pelaksanaan kuis berbasis sesi dengan kode akses 6 karakter untuk murid tanpa login.
- Mode tampil langsung (immediate) untuk latihan guru di kelas.
- Verifikasi akun guru oleh admin sebelum akun dapat digunakan.
- Modul siswa login (`StudentController.php`) yang masih tersedia di kode.

## 1.2 Tujuan

1. Menyediakan bank soal digital yang terorganisasi berdasarkan kelas, mata pelajaran, dan tingkat kesulitan.
2. Mempermudah guru menambah soal melalui formulir manual, pindaian foto, dan unggahan berkas.
3. Memungkinkan pelaksanaan kuis kelas tanpa mengharuskan setiap murid memiliki akun.
4. Memastikan hanya guru terverifikasi yang dapat mengelola soal dan menjalankan kuis.
5. Menyediakan rekap jawaban murid secara real-time untuk guru.

## 1.3 Manfaat

1. Guru tidak perlu menyusun ulang soal yang sama setiap semester.
2. Guru dapat memanfaatkan soal cetak yang sudah ada melalui foto atau berkas.
3. Murid kelas rendah dapat mengikuti kuis hanya dengan memasukkan nama.
4. Admin menjaga kualitas pengguna melalui verifikasi guru.
5. Hasil kuis tersimpan otomatis.

## 1.4 Pengguna Aplikasi

| No. | Pengguna | Syarat akses | Ringkasan tugas |
|---|---|---|---|
| 1 | Pengunjung | Tidak perlu akun | Membaca halaman utama, ke **Login** / **Daftar sebagai Guru**, atau membuka link kuis publik |
| 2 | Guru | Daftar, status `verified` | Mengelola soal, menjalankan kuis, memantau hasil |
| 3 | Admin | `role = admin` | Memverifikasi atau menolak guru |
| 4 | Murid tanpa login | Kode sesi/link dari guru | Memasukkan nama, menjawab, melihat hasil |
| 5 | Siswa akun login | `role = student` | Dashboard, menjawab soal, melihat hasil |

Pendaftaran di `/register` selalu membuat akun guru `pending` (`AuthController.php`).

---

# BAB II SPESIFIKASI APLIKASI

## 2.1 Spesifikasi Perangkat Keras

| Kebutuhan | Spesifikasi minimum | Keterangan |
|---|---|---|
| Perangkat pengguna | [ISI MANUAL: laptop/PC, tablet, atau HP Android berkamera] | Aplikasi responsif desktop/tablet/HP |
| Prosesor / RAM | [ISI MANUAL: contoh RAM minimal 4 GB untuk OCR browser] | OCR Tesseract.js berjalan di browser |
| Kamera/pemindai | [ISI MANUAL: kamera HP/webcam] | Untuk **Scan Soal** |
| Proyektor/layar kelas | [ISI MANUAL: opsional untuk kuis kelas] | Sesuai `PANDUAN_GURU_QUICK.md` |
| Server/hosting | [ISI MANUAL: CPU, RAM, penyimpanan server produksi] | Tidak ditemukan di kode |

## 2.2 Spesifikasi Perangkat Lunak

Tabel 2.1 — Berdasarkan kode.

| Komponen | Hasil pembacaan kode |
|---|---|
| Jenis | Aplikasi web (`routes/web.php`) |
| Backend | Laravel (`laravel/framework: ^13.8` di `composer.json`; dokumen lama menyebut Laravel 11) |
| Bahasa | PHP `^8.3` (`composer.json`) |
| Database | MySQL; tabel `users`, `kelas`, `kategori`, `soal`, `soal_sessions`, `soal_session_items`, `jawaban` + tabel bawaan Laravel |
| Frontend | Blade + Tailwind CSS + Vite (`tailwindcss ^4.0.0`, `vite ^8.0.0`) |
| Pustaka | `smalot/pdfparser ^2.12`; Tesseract.js v5 (CDN) untuk OCR; fallback `pdftotext`/`catdoc` di server |
| Peramban | [ISI MANUAL: Chrome/Edge terbaru] |
| OS server | [ISI MANUAL: Windows + XAMPP/Laragon atau Linux + Nginx/Apache] |

Akun bawaan seeder: Admin `admin@edukasi.local` / `password123` (`verified`); Guru contoh `guru@edukasi.local` / `password123` (`pending`). Kelas 1–6 SD; kategori `mudah`, `normal`, `sulit`.

## 2.3 Spesifikasi Pengguna

| Pengguna | Pengetahuan minimum | Perangkat disarankan |
|---|---|---|
| Guru | Mengoperasikan peramban, formulir, upload | Laptop/PC; HP berkamera |
| Admin | Memeriksa identitas pendaftar | Laptop/PC |
| Murid tanpa login | Membaca soal, mengeklik A–D | HP/tablet/laptop |
| Siswa login | Login email + password | HP/tablet/laptop |

## 2.4 Hak Akses Pengguna (Tabel Role x Fitur)

Tabel 2.2 — Berdasarkan `routes/web.php`, middleware, dan controller.

| Fitur / Menu | Pengunjung | Guru `verified` | Admin | Murid tanpa login | Siswa login |
|---|---|---|---|---|---|
| Halaman utama `/` | Ya | Ya | Ya | Ya | Ya |
| **Login**, **Daftar sebagai Guru** | Ya | Ya | — | — | Ya (login) |
| Dashboard Admin, **✓ Verifikasi**, **✕ Tolak** | Tidak | Tidak | Ya | Tidak | Tidak |
| Dashboard Guru, **＋ Tambah Soal**, **Quiziz — Atur & Mulai**, **Jalankan Soal Langsung**, **Lihat Semua Soal Saya →** | Tidak | Ya | Tidak | Tidak | Tidak |
| **Daftar Soal Saya**, **Filter**, **Reset**, **Edit**, **Hapus**, **▶ Jalankan** | Tidak | Ya (milik sendiri) | Tidak | Tidak | Tidak |
| **Buat Soal Baru** / **Simpan Soal** / **Batal** | Tidak | Ya | Tidak | Tidak | Tidak |
| **Scan Soal** / **Proses & Scan** / **💾 Simpan Semua Soal** | Tidak | Ya | Tidak | Tidak | Tidak |
| **Upload File Soal** / **📤 Upload & Proses** / **💾 Simpan Semua Soal** | Tidak | Ya | Tidak | Tidak | Tidak |
| **▶ Mulai Soal** / **▶ Mulai Session (Buat Session)** / **🔎 Tampilkan Langsung (Tanpa Session)** | Tidak | Ya | Tidak | Tidak | Tidak |
| Halaman sesi guru, **📋 Salin Link**, **📊 Lihat Hasil Jawaban Murid (Real-time)**, **✕ Tutup Session** | Tidak | Ya (milik sendiri) | Tidak | Tidak | Tidak |
| Immediate guru, **Jawab**, **Acak Ulang**, **Keluar** | Tidak | Ya | Tidak | Tidak | Tidak |
| Kuis publik `/quiz/{kode}/name`, jawab, hasil | Ya (kode valid, sesi aktif) | — | — | Ya | — |
| Modul siswa `/student/*` | Tidak | Tidak | Tidak | Tidak | Ya (`auth` saja) |
| Logout (ikon keluar navbar) | — | Ya | Ya | — | Ya |
| Cetak/export, profil, reset password | Tidak ada di kode | Tidak ada di kode | Tidak ada di kode | Tidak ada di kode | Tidak ada di kode |

Catatan: `AdminMiddleware` memeriksa `role === 'admin'`; `GuruMiddleware` memeriksa `role === 'guru'` + `status === 'verified'`; rute `/student/*` hanya memakai `auth`. Klaim "Export soal" dan "Generate soal AI" di `README.md` tidak memiliki rute/controller/tombol di kode.

---

# BAB III PANDUAN PENGGUNAAN

Aturan baca: pola **Nama Fitur → Tujuan → Langkah Penggunaan → Hasil**. Satu nomor = satu aksi. Nama tombol ditebalkan.

## 3.1 Membuka Aplikasi

### Fitur 3.1.1 — Membuka halaman utama

**Tujuan:** tiba di alamat aplikasi yang benar.

**Langkah penggunaan:**

1. Buka peramban versi terbaru.
2. Ketik alamat aplikasi pada address bar.
3. Tekan **Enter**.
4. Perhatikan navbar: logo **Web Edukasi** dan **SDN 2 Petungsewu**.

![Gambar 3.1 - Halaman utama, penanda: (1) logo Web Edukasi, (2) tombol Login, (3) tombol Daftar Gratis ✨](screenshots/3-01-halaman-utama.png)

**Hasil:** tampil tombol **Login**, **Daftar Gratis ✨**, **Mulai Sekarang 🚀**, **Daftar sebagai Guru**; bila sudah login tampil **Masuk Dashboard →**.

### Fitur 3.1.2 — Berpindah dari halaman utama saat sudah login

**Tujuan:** masuk dashboard tanpa login ulang.

**Langkah penggunaan:**

1. Buka halaman utama `/`.
2. Klik **Masuk Dashboard →**.
3. Jika guru, klik **＋ Buat Soal** untuk ke formulir soal.

**Hasil:** diarahkan ke dashboard sesuai peran.

## 3.2 Panduan untuk Guru

### Fitur 3.2.1 — Registrasi akun guru (`/register`)

**Tujuan:** membuat akun guru untuk diverifikasi admin.

**Langkah penggunaan:**

1. Klik **Daftar sebagai Guru** atau **Daftar Gratis ✨**.
2. Pada **Daftar Akun Guru**, klik area foto lalu **Pilih Foto →**.
3. Pilih foto (JPG/PNG/GIF, maks 2MB).
4. Isi **Nama Lengkap**.
5. Isi **Email**.
6. Isi **Password** (min 6 karakter).
7. Isi **Ulangi Password** sama persis.
8. Klik **Buat Akun ✨**.

![Gambar 3.2 - Formulir Daftar Akun Guru, penanda: (1) Pilih Foto, (2) Nama dan Email, (3) Buat Akun ✨](screenshots/3-02-register-guru.png)

**Hasil:** pesan `Registrasi berhasil! Silakan tunggu Admin memverifikasi akun Anda. Cek email Anda untuk informasi lebih lanjut.` Status `pending`.

### Fitur 3.2.2 — Login guru (`/login`)

**Tujuan:** masuk area guru terverifikasi.

**Langkah penggunaan:**

1. Klik **Login** atau **Mulai Sekarang 🚀**.
2. Isi **📧 Email**.
3. Isi **🔒 Password**.
4. Opsional: klik **LIHAT**/**SEMBUNYI**.
5. Klik **Masuk Sekarang →**.

![Gambar 3.3 - Formulir Masuk Akun, penanda: (1) Email, (2) Password, (3) Masuk Sekarang →](screenshots/3-03-login.png)

**Hasil:** diarahkan ke `/guru/dashboard` bila `verified`.

### Fitur 3.2.3 — Dashboard Guru (`/guru/dashboard`)

**Tujuan:** ringkasan soal dan jalan pintas tugas.

**Langkah penggunaan:**

1. Login sebagai guru.
2. Perhatikan kartu **Total Soal** dan **Terverifikasi ✓**.
3. Klik **Quiziz — Atur & Mulai** untuk kuis sesi.
4. Klik **Jalankan Soal Langsung** untuk latihan tanpa sesi.
5. Klik **Lihat Semua Soal Saya →** untuk bank soal.
6. Klik **＋ Tambah Soal** untuk soal manual.
7. Gunakan **🔍 Cari soal mudah...** / **normal...** / **sulit...**.

![Gambar 3.4 - Dashboard Guru, penanda: (1) ＋ Tambah Soal, (2) Quiziz — Atur & Mulai, (3) Lihat Semua Soal Saya →](screenshots/3-04-dashboard-guru.png)

**Hasil:** tampil kolom Mudah/Normal/Sulit + jumlah dan label kelas/kategori.

### Fitur 3.2.4 — Membuat soal manual (`/guru/soal/create`)

**Tujuan:** menambah satu soal pilihan ganda.

**Langkah penggunaan:**

1. Klik **＋ Tambah Soal**.
2. Pilih **Pilih Kelas**.
3. Pilih **Mata Pelajaran** (9 pilihan resmi).
4. Pilih **Tingkat Kesulitan** (🟢/🟡/🔴).
5. Isi **Pertanyaan** (min 10 karakter).
6. Isi **Pilihan A**.
7. Isi **Pilihan B**.
8. Isi **Pilihan C**.
9. Isi **Pilihan D**.
10. Pilih **Jawaban Benar** (A/B/C/D).
11. Klik **Simpan Soal** (atau **Batal**).

![Gambar 3.5 - Formulir Buat Soal Baru, penanda: (1) Kelas dan Mapel, (2) Pertanyaan dan A–D, (3) Simpan Soal](screenshots/3-05-buat-soal.png)

**Hasil:** pesan `Soal berhasil ditambahkan` di **Daftar Soal Saya**.

### Fitur 3.2.5 — Daftar dan filter soal (`/guru/soal`)

**Tujuan:** menemukan soal.

**Langkah penggunaan:**

1. Klik **📋 Soal Saya**.
2. Pilih **Mata Pelajaran** (atau **Semua Mata Pelajaran**).
3. Pilih **Tingkat Kesulitan** (atau **Semua Tingkat**).
4. Pilih **Kelas** (atau **Semua Kelas**).
5. Klik **Filter**.
6. Klik **Reset** untuk menghapus semua filter.
7. Klik **▶ Jalankan** / **Edit** / **Hapus** pada kartu soal.

![Gambar 3.6 - Daftar Soal Saya dan Filter, penanda: (1) Mata Pelajaran, (2) Filter, (3) ▶ Jalankan](screenshots/3-06-daftar-soal.png)

**Hasil:** `Menampilkan X dari Y soal` (10/halaman). Kartu menampilkan pertanyaan, mapel, kelas, kesulitan, A–D + **✓**, waktu **Dibuat**.

### Fitur 3.2.6 — Mengubah soal

**Tujuan:** memperbaiki soal milik sendiri.

**Langkah penggunaan:**

1. Buka **Daftar Soal Saya**.
2. Klik **Edit**.
3. Ubah field yang perlu.
4. Klik **Perbarui Soal** (atau **Batal**).

![Gambar 3.7 - Formulir Edit Soal, penanda: (1) field diubah, (2) Perbarui Soal](screenshots/3-07-edit-soal.png)

**Hasil:** pesan `Soal berhasil diperbarui`.

### Fitur 3.2.7 — Menghapus soal

**Tujuan:** menghapus soal tak terpakai.

**Langkah penggunaan:**

1. Buka **Daftar Soal Saya**.
2. Klik **Hapus**.
3. Konfirmasi `Yakin ingin menghapus soal ini?` → OK.

**Hasil:** pesan `Soal berhasil dihapus`.

### Fitur 3.2.8 — Scan Soal dari foto

**Tujuan:** foto cetak → soal digital via OCR.

**Langkah penggunaan:**

1. Klik **📸 Scan Soal**.
2. Klik area **Klik atau drag gambar soal ke sini**.
3. Pilih 1–10 gambar.
4. Periksa **Preview Gambar** (atau **Hapus Semua & Ganti**).
5. Pilih **Pilih Kelas** dan **Mata Pelajaran**.
6. Klik **Proses & Scan**.
7. Di **📋 Review Soal Hasil Scan**, periksa **Gambar Asli**.
8. Perbaiki **Pertanyaan**, **Pilihan A–D**, **Jawaban Benar**.
9. Klik **💾 Simpan Semua Soal** (atau **Scan Ulang**).

![Gambar 3.8 - Upload Scan Soal, penanda: (1) area upload, (2) Pilih Kelas, (3) Proses & Scan](screenshots/3-08-scan-upload.png)
![Gambar 3.9 - Review Scan, penanda: (1) Gambar Asli, (2) Pertanyaan dan Jawaban Benar, (3) 💾 Simpan Semua Soal](screenshots/3-09-scan-review.png)

**Hasil:** pesan `N soal berhasil ditambahkan dari scan!` (kesulitan bawaan `normal`).

### Fitur 3.2.9 — Upload File PDF/Word

**Tujuan:** ekstrak soal dari PDF/DOC/DOCX berteks.

**Langkah penggunaan:**

1. Klik **📄 Upload File**.
2. Klik area **Klik atau drag file ke sini**.
3. Pilih 1–20 berkas `.pdf/.doc/.docx` (maks 10MB/berkas).
4. Pilih **Pilih Kelas** dan **Mata Pelajaran**.
5. Klik **📤 Upload & Proses**.
6. Di **📋 Review Hasil Ekstraksi**, periksa **📄 Informasi File** dan **📝 Teks Hasil Ekstrak**.
7. Perbaiki tiap soal.
8. Klik **💾 Simpan Semua Soal** (atau **Upload Ulang**).

![Gambar 3.10 - Upload File, penanda: (1) area upload, (2) Mata Pelajaran, (3) 📤 Upload & Proses](screenshots/3-10-upload-file.png)
![Gambar 3.11 - Review Ekstraksi, penanda: (1) Informasi File, (2) Teks Hasil Ekstrak, (3) 💾 Simpan Semua Soal](screenshots/3-11-upload-review.png)

**Hasil:** pesan `N soal berhasil ditambahkan dari file upload!` Berkas pindaian gambar → gunakan **Scan Soal**.

### Fitur 3.2.10 — Memulai kuis sesi

**Tujuan:** membuat sesi + kode akses untuk murid.

**Langkah penggunaan:**

1. Klik **🚀 Mulai Quiz**.
2. Pilih **🟢 Mudah** / **🟡 Normal** / **🔴 Sulit** / **🎲 Campuran**.
3. Pilih **🏫 Pilih Kelas** (atau **-- Semua Kelas --**).
4. Klik **▶ Mulai Session (Buat Session)**.

![Gambar 3.12 - Mulai Soal, penanda: (1) Mudah s.d. Campuran, (2) Pilih Kelas, (3) ▶ Mulai Session (Buat Session)](screenshots/3-12-mulai-soal.png)

**Hasil:** sesi + kode 6 karakter. **🎲 Campuran** mengacak urutan.

### Fitur 3.2.11 — Membagikan kode dan link sesi

**Tujuan:** memberi akses ke murid.

**Langkah penggunaan:**

1. Catat **Kode Akses Murid**.
2. Klik **📋 Salin Link**.
3. Bagikan kode/link + instruksi.
4. Klik **📊 Lihat Hasil Jawaban Murid (Real-time)** untuk memantau.
5. Klik **✕ Tutup Session** bila selesai.

![Gambar 3.13 - Session Dibuka, penanda: (1) Kode Akses, (2) 📋 Salin Link, (3) 📊 Lihat Hasil Jawaban Murid (Real-time)](screenshots/3-13-display-session.png)

**Hasil:** status **🟢 AKTIF** dapat diakses murid; setelah ditutup **✖ SELESAI**.

### Fitur 3.2.12 — Memantau hasil dan menutup sesi

**Tujuan:** rekap + peringkat + tutup sesi.

**Langkah penggunaan:**

1. Klik **📊 Lihat Hasil Jawaban Murid (Real-time)**.
2. Baca **Total Soal**, **Total Jawab**, **Jawab Benar**, **Persentase Benar**.
3. Baca **🏆 Peringkat Murid**.
4. Baca tabel **Jawaban Murid** (**✓ Benar**/**✕ Salah**).
5. Klik **✕ Tutup Session** bila selesai.
6. Klik **← Kembali ke Dashboard**.

![Gambar 3.14 - Hasil Jawaban Murid, penanda: (1) kartu statistik, (2) tabel Jawaban Murid, (3) ✕ Tutup Session](screenshots/3-14-session-info.png)

**Hasil:** pesan `Sesi soal telah ditutup`.

### Fitur 3.2.13 — Kuis langsung tanpa sesi

**Tujuan:** latihan depan kelas tanpa kode.

**Langkah penggunaan:**

1. Klik **🔎 Tampilkan Langsung (Tanpa Session)** atau **Jalankan Soal Langsung**.
2. Pilih jawaban A–D.
3. Klik **Jawab**.
4. Benar → lanjut otomatis; salah → tetap + `Jawaban salah. Coba lagi.`
5. Klik **Acak Ulang** bila perlu.
6. Klik **Keluar**/**← Ubah Filter** + konfirmasi untuk berhenti.

![Gambar 3.15 - Soal langsung, penanda: (1) A–D, (2) Jawab, (3) Acak Ulang dan Keluar](screenshots/3-15-immediate.png)

**Hasil:** antrean immediate selesai → halaman **Semua Soal Selesai** (**Mulai Lagi** / **Kembali ke Dashboard**).

## 3.3 Panduan untuk Admin

### Fitur 3.3.1 — Login admin

**Tujuan:** masuk panel verifikasi.

**Langkah penggunaan:**

1. Klik **Login**.
2. Isi **📧 Email** admin.
3. Isi **🔒 Password**.
4. Klik **Masuk Sekarang →**.

**Hasil:** ke `/admin/dashboard`.

### Fitur 3.3.2 — Verifikasi guru

**Tujuan:** mengaktifkan guru.

**Langkah penggunaan:**

1. Baca kartu **Menunggu Verifikasi** / **Guru Terverifikasi**.
2. Cari nama/email/tanggal **Terdaftar**.
3. Klik **✓ Verifikasi**.

![Gambar 3.16 - Dashboard Admin, penanda: (1) nama dan email, (2) ✓ Verifikasi, (3) ✕ Tolak](screenshots/3-16-admin-dashboard.png)

**Hasil:** label **AKTIF ✓** + `Guru {nama} berhasil diverifikasi`.

### Fitur 3.3.3 — Menolak guru

**Tujuan:** menolak pendaftar tak memenuhi syarat.

**Langkah penggunaan:**

1. Cari guru di **Menunggu Verifikasi**.
2. Klik **✕ Tolak**.
3. Konfirmasi browser → OK.

**Hasil:** status `rejected`; tetap tidak bisa login.

## 3.4 Panduan untuk Murid Tanpa Login

### Fitur 3.4.1 — Input nama

**Tujuan:** mencatat identitas sebelum kuis.

**Langkah penggunaan:**

1. Buka link `/quiz/{kode}/name` dari guru.
2. Isi **Nama lengkap**.
3. Klik **Mulai Quiz →**.

![Gambar 3.17 - Input nama, penanda: (1) jumlah soal, (2) Nama lengkap, (3) Mulai Quiz →](screenshots/3-17-quiz-nama.png)

**Hasil:** ke soal pertama. Sesi tutup → halaman sesi berakhir.

### Fitur 3.4.2 — Menjawab soal

**Tujuan:** menjawab semua soal sesi.

**Langkah penggunaan:**

1. Baca **Soal N dari Total** + progres.
2. Pilih **A**/**B**/**C**/**D**.
3. Klik **Berikutnya →** (terakhir: **Selesai ✓**).
4. Klik **← Sebelumnya** bila perlu.

![Gambar 3.18 - Jawab kuis, penanda: (1) progres, (2) A–D, (3) Berikutnya →](screenshots/3-18-quiz-jawab.png)

**Hasil:** jawaban tersimpan; jawaban baru menimpa yang lama.

### Fitur 3.4.3 — Melihat hasil

**Tujuan:** skor + pembahasan.

**Langkah penggunaan:**

1. Buka hasil otomatis setelah soal terakhir.
2. Baca **Total Soal**, **Terjawab**, **Benar**, **Skor**.
3. Baca **📋 Detail Jawaban** (**✓ Benar**/**✕ Salah** + kunci bila salah).

![Gambar 3.19 - Hasil Quiz, penanda: (1) Skor, (2) Detail Jawaban, (3) ✓/✕](screenshots/3-19-quiz-hasil.png)

**Hasil:** `Sempurna!` ≥80%, `Bagus!` ≥60%, `Tetap Semangat!` di bawah itu.

## 3.5 Panduan untuk Siswa dengan Akun Login

Modul `/student/*` masih ada di kode; gunakan bila instansi menerbitkan akun siswa.

### Fitur 3.5.1 — Dashboard Siswa

**Tujuan:** statistik + pilih soal.

**Langkah penggunaan:**

1. Login siswa.
2. Klik **➡️ Lihat Semua Soal** atau **📊 Lihat Hasil**.
3. Klik **Mulai →** (kategori) / **Pilih →** (kelas).

![Gambar 3.20 - Dashboard Siswa, penanda: (1) ➡️ Lihat Semua Soal, (2) 📊 Lihat Hasil, (3) Mulai →](screenshots/3-20-student-dashboard.png)

**Hasil:** ke daftar terfilter.

### Fitur 3.5.2 — Daftar dan filter soal siswa

**Tujuan:** memilih soal.

**Langkah penggunaan:**

1. Klik **📝 Daftar Soal**.
2. Pilih **🏫 Kelas** dan **📚 Kategori**.
3. Klik **🔍 Cari Soal**.
4. Klik **Mulai Kuis 🚀** (status ✅/❌/⏳).

![Gambar 3.21 - Daftar Soal siswa, penanda: (1) Kelas dan Kategori, (2) 🔍 Cari Soal, (3) Mulai Kuis 🚀](screenshots/3-21-student-list.png)

**Hasil:** 12 soal/halaman.

### Fitur 3.5.3 — Kuis siswa

**Tujuan:** kirim jawaban + hasil instan.

**Langkah penggunaan:**

1. Klik opsi **A**–**D** hingga **✓**.
2. Klik **✓ Jawab Soal**.
3. Baca popup **Jawaban Benar! 🎉** / **Jawaban Salah! Coba Lagi! 📚**.
4. Klik **Soal Berikutnya →** / **📊 Lihat Hasil**.

![Gambar 3.22 - Kuis siswa, penanda: (1) A–D, (2) ✓ Jawab Soal, (3) popup hasil](screenshots/3-22-student-quiz.png)

**Hasil:** satu baris per siswa–soal; jawaban baru menimpa lama.

### Fitur 3.5.4 — Hasil siswa

**Tujuan:** nilai + riwayat terfilter.

**Langkah penggunaan:**

1. Klik **📊 Hasil**.
2. Baca **Nilai Kamu**, **Benar ✅**, **Salah ❌**, **Total 📚**.
3. Pilih filter lalu klik **🔍 Filter**.
4. Klik **← Kerjakan Soal Lagi** / **🏠 Ke Dashboard**.

![Gambar 3.23 - Hasil siswa, penanda: (1) Nilai Kamu, (2) 🔍 Filter, (3) ← Kerjakan Soal Lagi](screenshots/3-23-student-hasil.png)

**Hasil:** nilai = benar/total × 100%.

## 3.6 Logout

### Fitur 3.6.1 — Keluar

**Tujuan:** akhiri sesi di perangkat bersama.

**Langkah penggunaan:**

1. Klik tombol ikon keluar di navbar (di HP buka hamburger dulu).

**Hasil:** ke `/` + `Logout berhasil` (`POST /logout`).

---

# BAB IV TROUBLESHOOTING

Tabel 4.1 — Masalah | Penyebab | Solusi.

| No. | Masalah | Penyebab | Solusi |
|---|---|---|---|
| 1 | `Email atau password salah.` | Kredensial salah / belum `verified` | Cek ejaan; minta verifikasi admin |
| 2 | `Akun Anda belum diverifikasi oleh admin. Silakan tunggu.` | Status `pending`/`rejected` | Tunggu admin |
| 3 | `Unauthorized. Hanya guru verified yang dapat akses.` | Bukan guru verified | Gunakan akun guru AKTIF ✓ |
| 4 | `Unauthorized. Hanya admin yang dapat akses.` | Bukan admin | Gunakan akun admin |
| 5 | Registrasi gagal di field foto/email/password | Validasi: foto wajib maks 2MB; email unik; password min 6 + konfirmasi sama | Perbaiki field yang ditandai |
| 6 | Soal tidak tersimpan | Pertanyaan <10 karakter / dropdown kosong / mapel di luar 9 pilihan | Lengkapi semua field |
| 7 | `Anda tidak memiliki akses ke soal ini` | Soal milik guru lain | Hanya ubah milik sendiri |
| 8 | `Tidak ada soal dengan filter yang dipilih` | Filter tanpa hasil | Gunakan **🎲 Campuran** / **Semua Kelas** |
| 9 | `Data tidak valid` saat verifikasi | Bukan guru pending | Muat ulang dashboard |
| 10 | Murid tak bisa masuk (sesi berakhir) | Sesi `completed` | Minta sesi baru |
| 11 | Selalu kembali ke input nama | `nama` kosong | Isi nama + **Mulai Quiz →** |
| 12 | `Tidak ada soal yang terdeteksi.` (scan) | Foto blur/format salah | Foto ulang; format `1. ...` + `A)`; fallback **✏️ Input Manual** |
| 13 | `Tidak dapat mengekstrak teks...` (upload) | PDF pindaian/terenkripsi | Gunakan **Scan Soal** untuk pindaian |
| 14 | Review kosong / spinner terus | Pola tak dikenali / CDN diblokir | Cek koneksi; sederhanakan format |
| 15 | Tanpa export/cetak/profil/reset | Belum ada di kode | [ISI MANUAL: prosedur resmi] |
| 16 | Error DB setelah instalasi | `.env`/migrasi belum benar | Cek `DB_*`; `migrate:fresh --seed`; `config:clear` |
| 17 | Rute/aset tidak update | Cache lama | `route:clear`, `cache:clear`, `npm run build` |

---

# BAB V PENUTUP

## 5.1 Kesimpulan

1. Alur lengkap: daftar → verifikasi → kelola soal → kuis sesi/langsung → jawab tanpa login → pantau hasil.
2. Bank soal: manual + foto (OCR) + PDF/DOC/DOCX, filter kelas/mapel/kesulitan.
3. Batasan kode: guru `verified`, pemilik soal/sesi, sesi `active`.

## 5.2 Saran

1. Tetapkan URL resmi, admin cadangan, jam support; ganti semua `[ISI MANUAL: ...]`.
2. Lengkapi 24 screenshot Lampiran A dengan data produksi.
3. Sosialisasikan format soal standar untuk akurasi OCR/ekstraksi.
4. Tetapkan retensi sesi/jawaban.
5. Kembangkan export, profil, reset password bila dibutuhkan (belum ada di kode).

[ISI MANUAL: tanda tangan penanggung jawab dokumen]

---

# LAMPIRAN

## A. Daftar Screenshot (24 file)

`3-01-halaman-utama.png`, `3-02-register-guru.png`, `3-03-login.png`, `3-04-dashboard-guru.png`, `3-05-buat-soal.png`, `3-06-daftar-soal.png`, `3-07-edit-soal.png`, `3-08-scan-upload.png`, `3-09-scan-review.png`, `3-10-upload-file.png`, `3-11-upload-review.png`, `3-12-mulai-soal.png`, `3-13-display-session.png`, `3-14-session-info.png`, `3-15-immediate.png`, `3-16-admin-dashboard.png`, `3-17-quiz-nama.png`, `3-18-quiz-jawab.png`, `3-19-quiz-hasil.png`, `3-20-student-dashboard.png`, `3-21-student-list.png`, `3-22-student-quiz.png`, `3-23-student-hasil.png`, `logo-sekolah.png`. Rincian halaman dan penanda lihat `08-lampiran.md`.

## B. Contoh Output

Kode sesi 6 karakter (contoh `A1B2C3`), link `[ISI MANUAL: basis URL]/quiz/A1B2C3/name`, status **🟢 AKTIF**/**✖ SELESAI**, rekap Total/ Benar/%, podium 5 besar, tabel Nama–Jawaban–Status, pesan sistem persis seperti Bab III–IV.

## C. Kontak Support

[ISI MANUAL: nama, WA/telepon, email admin/operator, jam layanan, eskalasi teknis]
