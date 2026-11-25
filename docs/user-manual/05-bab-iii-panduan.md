# BAB III PANDUAN PENGGUNAAN

> Aturan baca: setiap fitur ditulis dengan pola **Nama Fitur → Tujuan → Langkah Penggunaan → Hasil**. Nama menu dan tombol ditebalkan persis seperti di aplikasi. Satu nomor = satu aksi.

## 3.1 Membuka Aplikasi (Halaman Utama `/`)

### Fitur 3.1.1 — Membuka halaman utama

**Tujuan:** memastikan pengguna tiba di alamat aplikasi yang benar sebelum login atau mengikuti kuis.

**Langkah penggunaan:**

1. Buka peramban (Chrome/Edge/Firefox versi terbaru).
2. Ketik alamat aplikasi pada address bar.
3. Tekan **Enter**.
4. Perhatikan bagian atas (navbar) menampilkan logo **Web Edukasi** dan tulisan **SDN 2 Petungsewu**.

![Gambar 3.1 - Halaman utama, penanda: (1) logo Web Edukasi, (2) tombol Login, (3) tombol Daftar Gratis ✨](screenshots/3-01-halaman-utama.png)

**Hasil:** tampil halaman utama dengan tombol **Login**, **Daftar Gratis ✨**, **Mulai Sekarang 🚀**, dan **Daftar sebagai Guru**. Jika sudah login, tampil tombol **Masuk Dashboard →** sesuai peran.

### Fitur 3.1.2 — Berpindah dari halaman utama saat sudah login

**Tujuan:** masuk ke dashboard yang sesuai tanpa login ulang.

**Langkah penggunaan:**

1. Buka halaman utama `/`.
2. Klik tombol **Masuk Dashboard →**.
3. Jika Anda guru, klik tombol **＋ Buat Soal** untuk langsung ke formulir soal.

**Hasil:** Anda diarahkan ke `/guru/dashboard`, `/admin/dashboard`, atau `/student/dashboard` sesuai peran.

---

## 3.2 Panduan untuk Guru

Akun guru harus berstatus `verified` untuk membuka semua menu di bawah ini. Jika belum diverifikasi, login akan gagal dengan pesan pada Bab IV.

### Fitur 3.2.1 — Registrasi akun guru (`/register`)

**Tujuan:** membuat akun guru baru yang akan diperiksa admin.

**Langkah penggunaan:**

1. Buka halaman utama.
2. Klik **Daftar sebagai Guru** atau **Daftar Gratis ✨**.
3. Pada halaman **Daftar Akun Guru**, klik area foto lalu klik **Pilih Foto →**.
4. Pilih berkas foto (JPG, PNG, GIF, maksimal 2MB) pada perangkat Anda.
5. Isi field **Nama Lengkap**.
6. Isi field **Email**.
7. Isi field **Password** (minimal 6 karakter).
8. Isi field **Ulangi Password** dengan nilai yang sama persis.
9. Perhatikan kotak info bahwa Anda terdaftar sebagai **Guru** dan akun aktif setelah diverifikasi admin.
10. Klik **Buat Akun ✨**.

![Gambar 3.2 - Formulir Daftar Akun Guru, penanda: (1) Pilih Foto, (2) field Nama Lengkap dan Email, (3) tombol Buat Akun ✨](screenshots/3-02-register-guru.png)

**Hasil:** kembali ke halaman utama dengan pesan `Registrasi berhasil! Silakan tunggu Admin memverifikasi akun Anda. Cek email Anda untuk informasi lebih lanjut.` Akun tersimpan dengan peran guru dan status `pending`. Sudah punya akun? Klik **Login di sini**.

Field dan validasi aktual (`AuthController.php`):

| Field | Aturan | Pesan/error jika dilanggar |
|---|---|---|
| Foto Profil (`foto`) | Wajib, gambar `jpeg,png,jpg,gif`, maks 2MB | Pesan validasi di bawah field foto |
| Nama Lengkap (`name`) | Wajib, maks 255 karakter | Pesan validasi di bawah field |
| Email (`email`) | Wajib, format email, tidak boleh sama dengan akun lain | Pesan validasi di bawah field |
| Password (`password`) | Wajib, minimal 6 karakter, harus sama dengan konfirmasi | Pesan validasi di bawah field |

### Fitur 3.2.2 — Login guru (`/login`)

**Tujuan:** masuk ke area guru terverifikasi.

**Langkah penggunaan:**

1. Buka halaman utama.
2. Klik **Login** atau **Mulai Sekarang 🚀**.
3. Pada halaman **Masuk Akun**, isi field **📧 Email**.
4. Isi field **🔒 Password**.
5. Opsional: klik **LIHAT** untuk menampilkan password, klik **SEMBUNYI** untuk menyembunyikan kembali.
6. Klik **Masuk Sekarang →**.
7. Jika belum punya akun, klik **Daftar sebagai Guru**.

![Gambar 3.3 - Formulir Masuk Akun, penanda: (1) field Email, (2) field Password, (3) tombol Masuk Sekarang →](screenshots/3-03-login.png)

**Hasil:** jika email, password, dan status `verified` benar, Anda diarahkan ke `/guru/dashboard`. Jika salah, tampil pesan pada Bab IV.

### Fitur 3.2.3 — Memahami Dashboard Guru (`/guru/dashboard`)

**Tujuan:** melihat ringkasan soal dan jalan pintas ke semua tugas guru.

**Langkah penggunaan:**

1. Login sebagai guru.
2. Perhatikan sapaan nama dan foto profil di kartu atas.
3. Perhatikan kartu **Total Soal**.
4. Perhatikan kartu **Status Akun** yang harus bertuliskan **Terverifikasi ✓**.
5. Untuk membuat kuis sesi, klik **Quiziz — Atur & Mulai**.
6. Untuk latihan langsung tanpa sesi, klik **Jalankan Soal Langsung**.
7. Untuk mengelola bank soal, klik **Lihat Semua Soal Saya →**.
8. Untuk menambah soal manual, klik **＋ Tambah Soal**.
9. Gunakan kolom **🔍 Cari soal mudah...**, **🔍 Cari soal normal...**, dan **🔍 Cari soal sulit...** untuk menyaring kartu soal per tingkat kesulitan.

![Gambar 3.4 - Dashboard Guru, penanda: (1) tombol ＋ Tambah Soal, (2) tombol Quiziz — Atur & Mulai, (3) tombol Lihat Semua Soal Saya →](screenshots/3-04-dashboard-guru.png)

**Hasil:** tampil tiga kolom **Mudah**, **Normal**, dan **Sulit** beserta jumlah soal, nama kelas, dan kategori tiap soal.

### Fitur 3.2.4 — Membuat soal manual (`/guru/soal/create`)

**Tujuan:** menambah satu soal pilihan ganda ke bank soal milik guru.

**Langkah penggunaan:**

1. Dari dashboard, klik **＋ Tambah Soal**.
2. Pada halaman **Buat Soal Baru**, buka dropdown **Pilih Kelas**.
3. Pilih satu kelas (misalnya **Kelas 1 SD**).
4. Buka dropdown **Mata Pelajaran**.
5. Pilih satu mata pelajaran (Agama, PPKn, Bahasa Indonesia, Matematika, IPA, IPS, PJOK, Seni Budaya, Bahasa Inggris).
6. Buka dropdown **Tingkat Kesulitan**.
7. Pilih **🟢 Mudah**, **🟡 Normal**, atau **🔴 Sulit**.
8. Isi **Pertanyaan** minimal 10 karakter.
9. Isi **Pilihan A**.
10. Isi **Pilihan B**.
11. Isi **Pilihan C**.
12. Isi **Pilihan D**.
13. Buka dropdown **Jawaban Benar**.
14. Pilih **A**, **B**, **C**, atau **D**.
15. Klik **Simpan Soal**.
16. Jika batal, klik **Batal**.
17. Jalan pintas lain di halaman ini: klik **📸 Scan Soal** untuk pindah ke Scan Soal, atau klik **📄 Upload PDF/Word** untuk pindah ke Upload File.

![Gambar 3.5 - Formulir Buat Soal Baru, penanda: (1) Pilih Kelas dan Mata Pelajaran, (2) Pertanyaan dan Pilihan A–D, (3) tombol Simpan Soal](screenshots/3-05-buat-soal.png)

**Hasil:** Anda diarahkan ke **Daftar Soal Saya** dengan pesan `Soal berhasil ditambahkan`. Soal hanya terlihat dan hanya dapat diubah oleh guru pembuatnya.

### Fitur 3.2.5 — Melihat dan memfilter Daftar Soal Saya (`/guru/soal`)

**Tujuan:** menemukan soal berdasarkan mata pelajaran, tingkat kesulitan, dan kelas.

**Langkah penggunaan:**

1. Di navbar, klik **📋 Soal Saya**.
2. Pada panel **🔍 Filter Soal**, buka dropdown **Mata Pelajaran**.
3. Pilih satu mata pelajaran atau biarkan **Semua Mata Pelajaran**.
4. Buka dropdown **Tingkat Kesulitan**.
5. Pilih **🟢 Mudah**, **🟡 Normal**, **🔴 Sulit**, atau **Semua Tingkat**.
6. Buka dropdown **Kelas**.
7. Pilih satu kelas atau biarkan **Semua Kelas**.
8. Klik **Filter**.
9. Untuk menghapus semua filter, klik **Reset**.
10. Untuk menghapus satu filter aktif, klik tanda **✕** pada label filter aktif.
11. Pindah halaman dengan tautan pagination di bawah daftar (10 soal per halaman).
12. Dari tiap kartu soal, klik **▶ Jalankan** untuk ke halaman **▶ Mulai Soal**, klik **Edit** untuk mengubah, atau gunakan **Hapus** untuk menghapus.

![Gambar 3.6 - Daftar Soal Saya dan Filter Soal, penanda: (1) dropdown Mata Pelajaran, (2) tombol Filter, (3) tombol ▶ Jalankan pada kartu soal](screenshots/3-06-daftar-soal.png)

**Hasil:** tampil teks `Menampilkan X dari Y soal`. Tiap kartu menampilkan pertanyaan, label mata pelajaran, kelas, tingkat kesulitan, pilihan A–D dengan tanda **✓** pada jawaban benar, dan waktu **Dibuat**. Jika kosong, tampil pesan `Anda belum membuat soal apapun.` dan tombol **Buat Soal Sekarang**.

### Fitur 3.2.6 — Mengubah soal (`/guru/soal/{id}/edit`)

**Tujuan:** memperbaiki soal milik sendiri tanpa membuat ulang.

**Langkah penggunaan:**

1. Buka **Daftar Soal Saya**.
2. Pada kartu soal yang diinginkan, klik **Edit**.
3. Pada halaman **Edit Soal**, ubah field yang diperlukan (**Pilih Kelas**, **Mata Pelajaran**, **Tingkat Kesulitan**, **Pertanyaan**, **Pilihan A–D**, **Jawaban Benar**).
4. Klik **Perbarui Soal**.
5. Jika batal, klik **Batal**.

![Gambar 3.7 - Formulir Edit Soal, penanda: (1) field yang diubah, (2) tombol Perbarui Soal](screenshots/3-07-edit-soal.png)

**Hasil:** kembali ke **Daftar Soal Saya** dengan pesan `Soal berhasil diperbarui`. Jika soal milik guru lain, tampil `Anda tidak memiliki akses ke soal ini`.

### Fitur 3.2.7 — Menghapus soal

**Tujuan:** menghapus soal yang sudah tidak dipakai.

**Langkah penggunaan:**

1. Buka **Daftar Soal Saya**.
2. Pada kartu soal yang diinginkan, klik **Hapus**.
3. Pada dialog konfirmasi browser, pilih OK untuk melanjutkan atau Batal untuk membatalkan (`Yakin ingin menghapus soal ini?`).

**Hasil:** soal terhapus dan tampil pesan `Soal berhasil dihapus`.

### Fitur 3.2.8 — Scan Soal dari foto (`/guru/soal/scan/upload` → review → simpan)

**Tujuan:** mengubah foto soal cetak menjadi soal digital melalui OCR di browser.

**Langkah penggunaan:**

1. Buka **Daftar Soal Saya**.
2. Klik **📸 Scan Soal**.
3. Pada halaman **📸 Scan Soal Otomatis**, klik area **Klik atau drag gambar soal ke sini**.
4. Pilih 1–10 berkas gambar (maks 5MB per foto menurut teks halaman; validasi server maks 5MB per gambar).
5. Periksa pratinjau pada **Preview Gambar**.
6. Jika salah pilih, klik **Hapus Semua & Ganti**.
7. Pada **Pilih Kelas**, pilih satu kelas.
8. Pada **Mata Pelajaran**, pilih satu mata pelajaran.
9. Klik **Proses & Scan**.
10. Tunggu bilah progres OCR hingga selesai.
11. Pada halaman **📋 Review Soal Hasil Scan**, periksa kolom **Gambar Asli** di kiri dan formulir soal di kanan.
12. Opsional: klik **📄 Lihat Teks Hasil Ekstrak (Debug)** untuk melihat teks mentah.
13. Perbaiki **Pertanyaan**, **Pilihan A**, **Pilihan B**, **Pilihan C**, **Pilihan D**, dan **Jawaban Benar** tiap soal.
14. Jika satu hasil salah terdeteksi, klik **✕ Hapus** pada kartu soal tersebut.
15. Klik **💾 Simpan Semua Soal**.
16. Jika ingin mengulang, klik **Scan Ulang**.
17. Jika OCR tidak menemukan soal, klik **🔄 Scan Ulang** atau **✏️ Input Manual**.

![Gambar 3.8 - Upload Scan Soal, penanda: (1) area upload gambar, (2) dropdown Pilih Kelas, (3) tombol Proses & Scan](screenshots/3-08-scan-upload.png)
![Gambar 3.9 - Review Soal Hasil Scan, penanda: (1) Gambar Asli, (2) formulir Pertanyaan dan Jawaban Benar, (3) tombol 💾 Simpan Semua Soal](screenshots/3-09-scan-review.png)

**Hasil:** Anda diarahkan ke **Daftar Soal Saya** dengan pesan `N soal berhasil ditambahkan dari scan!` Tingkat kesulitan bawaan dari fitur ini adalah `normal`. Format foto yang paling mudah terbaca: `1. Pertanyaan?` diikuti `A) Jawaban`.

### Fitur 3.2.9 — Upload File Soal PDF/Word (`/guru/soal/upload/file` → review → simpan)

**Tujuan:** mengekstrak soal dari berkas PDF, DOC, atau DOCX yang berisi teks.

**Langkah penggunaan:**

1. Buka **Daftar Soal Saya**.
2. Klik **📄 Upload File**.
3. Pada halaman **📄 Upload File Soal**, klik area **Klik atau drag file ke sini**.
4. Pilih 1–20 berkas (`.pdf`, `.doc`, `.docx`, maks 10MB per berkas).
5. Periksa daftar pada **File yang dipilih**.
6. Jika salah pilih, klik **Hapus & Ganti**.
7. Pada **Pilih Kelas**, pilih satu kelas.
8. Pada **Mata Pelajaran**, pilih satu mata pelajaran.
9. Klik **📤 Upload & Proses**.
10. Tunggu overlay **Mengupload file...** hingga selesai.
11. Pada halaman **📋 Review Hasil Ekstraksi**, baca panel **📄 Informasi File** dan **📝 Teks Hasil Ekstrak**.
12. Perbaiki **Pertanyaan**, **Pilihan A–D**, dan **Jawaban Benar** tiap soal.
13. Jika satu hasil tidak valid, klik **✕ Hapus**.
14. Klik **💾 Simpan Semua Soal**.
15. Jika ingin mengulang, klik **Upload Ulang**.
16. Jika tidak ada teks terdeteksi, klik **🔄 Upload Ulang** atau **✏️ Input Manual**.

![Gambar 3.10 - Upload File Soal, penanda: (1) area upload file, (2) dropdown Mata Pelajaran, (3) tombol 📤 Upload & Proses](screenshots/3-10-upload-file.png)
![Gambar 3.11 - Review Hasil Ekstraksi, penanda: (1) Informasi File, (2) Teks Hasil Ekstrak, (3) tombol 💾 Simpan Semua Soal](screenshots/3-11-upload-review.png)

**Hasil:** Anda diarahkan ke **Daftar Soal Saya** dengan pesan `N soal berhasil ditambahkan dari file upload!` Untuk berkas pindaian berupa gambar, gunakan fitur **Scan Soal**, bukan Upload File.

### Fitur 3.2.10 — Memulai kuis sesi (`/guru/quiz/start` → buat sesi)

**Tujuan:** membuat sesi kuis dengan kode akses agar banyak murid dapat menjawab soal yang sama.

**Langkah penggunaan:**

1. Di navbar, klik **🚀 Mulai Quiz**.
2. Pada halaman **▶ Mulai Soal**, pilih satu tingkat kesulitan: **🟢 Mudah**, **🟡 Normal**, **🔴 Sulit**, atau **🎲 Campuran**.
3. Buka dropdown **🏫 Pilih Kelas**.
4. Pilih satu kelas atau biarkan **-- Semua Kelas --**.
5. Klik **▶ Mulai Session (Buat Session)**.
6. Jika ingin latihan langsung tanpa kode sesi, klik **🔎 Tampilkan Langsung (Tanpa Session)** (lihat Fitur 3.2.13).
7. Jika batal, klik **Batal** atau **← Kembali ke Dashboard**.

![Gambar 3.12 - Halaman Mulai Soal, penanda: (1) pilihan 🟢 Mudah s.d. 🎲 Campuran, (2) dropdown 🏫 Pilih Kelas, (3) tombol ▶ Mulai Session (Buat Session)](screenshots/3-12-mulai-soal.png)

**Hasil:** sesi baru dibuat dengan kode 6 karakter dan Anda diarahkan ke halaman **Session Quiz Dibuka**. Jika tidak ada soal yang cocok, tampil `Tidak ada soal dengan filter yang dipilih`. Pilihan **🎲 Campuran** mengacak urutan soal.

### Fitur 3.2.11 — Membagikan kode dan link sesi (`/guru/quiz/{session}/display`)

**Tujuan:** memberikan akses kuis kepada murid.

**Langkah penggunaan:**

1. Setelah sesi dibuat, catat **Kode Akses Murid** 6 karakter.
2. Klik kolom link lalu klik **📋 Salin Link**.
3. Tulis kode di papan tulis atau kirim link kepada murid.
4. Sampaikan instruksi murid: buka link, masukkan nama lengkap, jawab satu per satu, lalu lihat hasil.
5. Untuk memantau jawaban, klik **📊 Lihat Hasil Jawaban Murid (Real-time)**.
6. Untuk menutup akses, klik **✕ Tutup Session** dan konfirmasi.
7. Untuk kembali, klik **← Kembali ke Dashboard**.

![Gambar 3.13 - Session Quiz Dibuka, penanda: (1) Kode Akses Murid, (2) tombol 📋 Salin Link, (3) tombol 📊 Lihat Hasil Jawaban Murid (Real-time)](screenshots/3-13-display-session.png)

**Hasil:** selama status **🟢 AKTIF**, murid dapat masuk. Setelah ditutup, status menjadi **✖ SELESAI** dan murid melihat halaman sesi berakhir.

### Fitur 3.2.12 — Memantau hasil dan menutup sesi (`/guru/quiz/{session}/info`, tutup sesi)

**Tujuan:** melihat siapa menjawab apa, peringkat murid, dan menutup sesi yang selesai.

**Langkah penggunaan:**

1. Dari halaman sesi, klik **📊 Lihat Hasil Jawaban Murid (Real-time)**.
2. Pada halaman **📊 Hasil Jawaban Murid**, catat **Session** (kode sesi).
3. Baca empat kartu: **Total Soal**, **Total Jawab**, **Jawab Benar**, **Persentase Benar**.
4. Baca podium **🏆 Peringkat Murid** (5 peserta terbaik).
5. Pada tiap blok **Soal N**, baca pilihan A–D dan penanda **✓ Jawaban Benar**.
6. Pada tabel **Jawaban Murid**, baca kolom **Nama Murid**, **Jawaban**, dan **Status** (**✓ Benar** / **✕ Salah**).
7. Jika soal belum dijawab, biarkan pesan `Belum ada murid yang menjawab soal ini.`
8. Untuk menutup dari halaman ini, klik **✕ Tutup Session** dan konfirmasi.
9. Untuk kembali, klik **← Kembali ke Dashboard**.

![Gambar 3.14 - Hasil Jawaban Murid, penanda: (1) kartu Total Jawab dan Persentase Benar, (2) tabel Jawaban Murid, (3) tombol ✕ Tutup Session](screenshots/3-14-session-info.png)

**Hasil:** sesi yang ditutup menyimpan waktu selesai dan menampilkan pesan `Sesi soal telah ditutup`. Murid tidak dapat mengirim jawaban baru ke sesi yang sudah `completed`.

### Fitur 3.2.13 — Kuis langsung tanpa sesi (immediate)

**Tujuan:** menampilkan soal satu per satu untuk latihan guru di depan kelas tanpa kode sesi.

**Langkah penggunaan:**

1. Dari halaman **▶ Mulai Soal**, klik **🔎 Tampilkan Langsung (Tanpa Session)**.
2. Atau dari dashboard, klik **Jalankan Soal Langsung**.
3. Pada halaman **Soal N / Total**, baca pertanyaan dan pilihan A–D.
4. Pilih satu jawaban **A**, **B**, **C**, atau **D**.
5. Klik **Jawab**.
6. Jika benar, otomatis lanjut ke soal berikutnya dengan pesan `Benar! Lanjut ke soal berikutnya.`
7. Jika salah, tetap di soal yang sama dengan pesan `Jawaban salah. Coba lagi.`
8. Untuk mengacak sisa soal, klik **Acak Ulang**.
9. Untuk keluar, klik **Keluar** atau **← Ubah Filter**, lalu konfirmasi pada dialog **Konfirmasi**.
10. Jika semua soal selesai, klik **Mulai Lagi** atau **Kembali ke Dashboard** pada halaman **Semua Soal Selesai**.

![Gambar 3.15 - Soal langsung guru, penanda: (1) pilihan jawaban A–D, (2) tombol Jawab, (3) tombol Acak Ulang dan Keluar](screenshots/3-15-immediate.png)

**Hasil:** jawaban benar memajukan posisi soal; jawaban salah menahan posisi. Keluar akan menghapus antrean immediate di sesi browser guru.

---

## 3.3 Panduan untuk Admin

### Fitur 3.3.1 — Login admin (`/login`)

**Tujuan:** masuk ke panel verifikasi guru.

**Langkah penggunaan:**

1. Buka halaman utama.
2. Klik **Login**.
3. Isi **📧 Email** dengan email admin.
4. Isi **🔒 Password** admin.
5. Klik **Masuk Sekarang →**.

**Hasil:** Anda diarahkan ke `/admin/dashboard`. Akun bawaan: `admin@edukasi.local` / `password123` (status `verified`).

### Fitur 3.3.2 — Memverifikasi guru (`/admin/dashboard` → Verifikasi)

**Tujuan:** mengaktifkan akun guru agar dapat login dan mengelola soal.

**Langkah penggunaan:**

1. Login sebagai admin.
2. Pada **Dashboard Admin**, baca kartu **Menunggu Verifikasi** dan **Guru Terverifikasi**.
3. Pada bagian **Menunggu Verifikasi**, cari nama, email, dan tanggal **Terdaftar**.
4. Pada baris guru tersebut, klik **✓ Verifikasi**.
5. Untuk tampilan HP, buka kartu guru lalu klik **✓ Verifikasi**.

![Gambar 3.16 - Dashboard Admin bagian Menunggu Verifikasi, penanda: (1) nama dan email guru, (2) tombol ✓ Verifikasi, (3) tombol ✕ Tolak](screenshots/3-16-admin-dashboard.png)

**Hasil:** guru berpindah ke bagian **Guru Terverifikasi** dengan label **AKTIF ✓** dan pesan `Guru {nama} berhasil diverifikasi`. Jika data tidak valid, tampil `Data tidak valid`.

### Fitur 3.3.3 — Menolak guru (`/admin/dashboard` → Tolak)

**Tujuan:** menolak pendaftaran guru yang tidak memenuhi syarat.

**Langkah penggunaan:**

1. Login sebagai admin.
2. Pada bagian **Menunggu Verifikasi**, cari guru yang dimaksud.
3. Klik **✕ Tolak**.
4. Pada dialog konfirmasi browser, pilih OK untuk melanjutkan.

**Hasil:** status guru menjadi `rejected` dengan pesan `Guru {nama} ditolak`. Akun tersebut tetap tidak dapat login karena hanya status `verified` yang diizinkan masuk.

---

## 3.4 Panduan untuk Murid Tanpa Login (Kuis Publik)

Murid tidak memerlukan akun. Guru memberikan kode 6 karakter atau link `/quiz/{kode}`.

### Fitur 3.4.1 — Memasukkan nama (`/quiz/{sessionCode}/name`)

**Tujuan:** mencatat identitas murid sebelum mengerjakan.

**Langkah penggunaan:**

1. Buka link dari guru atau buka `/quiz/{kode}/name`.
2. Pada halaman **Siap bermain?**, baca jumlah **soal siap dijawab**.
3. Isi field **Nama lengkap**.
4. Klik **Mulai Quiz →**.

![Gambar 3.17 - Halaman input nama murid, penanda: (1) jumlah soal, (2) field Nama lengkap, (3) tombol Mulai Quiz →](screenshots/3-17-quiz-nama.png)

**Hasil:** Anda diarahkan ke soal pertama `/quiz/{kode}?nama=...`. Jika sesi sudah ditutup, tampil halaman sesi berakhir.

### Fitur 3.4.2 — Menjawab soal (`/quiz/{sessionCode}?nama=...&soal=N`)

**Tujuan:** menjawab semua soal dalam sesi dan berpindah soal.

**Langkah penggunaan:**

1. Baca **Soal N dari Total** dan bilah progres persentase.
2. Baca **Pertanyaan**, label kelas, kategori, dan tingkat kesulitan.
3. Klik satu pilihan **A**, **B**, **C**, atau **D**.
4. Klik **Berikutnya →** (atau **Selesai ✓** pada soal terakhir).
5. Untuk kembali, klik **← Sebelumnya**.
6. Jika sudah pernah menjawab, perhatikan **Catatan** bahwa jawaban baru menggantikan jawaban lama.

![Gambar 3.18 - Halaman jawab kuis murid, penanda: (1) progres Soal N dari Total, (2) pilihan A–D, (3) tombol Berikutnya →](screenshots/3-18-quiz-jawab.png)

**Hasil:** jawaban tersimpan otomatis (nama murid + identifier perangkat). Soal berikutnya tampil. Jika sesi ditutup saat menjawab, pengiriman ditolak.

### Fitur 3.4.3 — Melihat hasil (`/quiz/{sessionCode}/result`)

**Tujuan:** mengetahui skor dan pembahasan jawaban sendiri.

**Langkah penggunaan:**

1. Setelah soal terakhir, Anda diarahkan otomatis ke halaman **Hasil Quiz**.
2. Baca kartu **Total Soal**, **Terjawab**, **Benar**, dan **Skor** (%).
3. Baca **Tingkat Pencapaian** dan nama serta kode sesi Anda.
4. Pada **📋 Detail Jawaban**, baca tiap blok **Soal N** berlabel **✓ Benar** atau **✕ Salah**.
5. Untuk jawaban salah, baca baris **Jawaban Benar**.
6. Ikuti pesan penutup: hubungi guru jika ada pertanyaan.

![Gambar 3.19 - Hasil Quiz murid, penanda: (1) kartu Skor, (2) Detail Jawaban, (3) label ✓ Benar / ✕ Salah](screenshots/3-19-quiz-hasil.png)

**Hasil:** tampil emotikon dan judul sesuai skor: `Sempurna!` (≥80%), `Bagus!` (≥60%), atau `Tetap Semangat!` (<60%). Hasil tersimpan dan dapat dipantau guru.

---

## 3.5 Panduan untuk Siswa dengan Akun Login

> Modul ini masih tersedia di kode (`StudentController.php`, `resources/views/student/*`, rute `/student/*`). Gunakan bagian ini jika instansi menerbitkan akun siswa. Alur tanpa login pada 3.4 tetap menjadi alur utama untuk murid SD.

### Fitur 3.5.1 — Membuka Dashboard Siswa (`/student/dashboard`)

**Tujuan:** melihat statistik pribadi dan memilih soal.

**Langkah penggunaan:**

1. Login dengan akun siswa.
2. Pada sapaan **Halo, {nama}!**, klik **➡️ Lihat Semua Soal** untuk ke daftar soal.
3. Klik **📊 Lihat Hasil** untuk ke rekap nilai.
4. Baca kartu **Soal Terjawab**, **Jawaban Benar**, dan **Nilai Rata-rata**.
5. Pada bagian **Pilih Kategori**, klik **Mulai →** pada kategori yang diinginkan.
6. Pada bagian **Pilih Kelas**, klik **Pilih →** pada kelas yang diinginkan.

![Gambar 3.20 - Dashboard Siswa, penanda: (1) tombol ➡️ Lihat Semua Soal, (2) tombol 📊 Lihat Hasil, (3) tombol Mulai → pada kategori](screenshots/3-20-student-dashboard.png)

**Hasil:** Anda diarahkan ke **Daftar Soal** yang sudah terfilter sesuai kategori/kelas yang diklik.

### Fitur 3.5.2 — Memfilter dan membuka kuis (`/student/soal`)

**Tujuan:** memilih soal yang akan dikerjakan.

**Langkah penggunaan:**

1. Di navbar siswa, klik **📝 Daftar Soal**.
2. Pada panel filter, buka dropdown **🏫 Kelas**.
3. Pilih **Semua Kelas** atau satu kelas.
4. Buka dropdown **📚 Kategori**.
5. Pilih **Semua Kategori** atau satu kategori.
6. Klik **🔍 Cari Soal**.
7. Perhatikan ikon status tiap kartu: **✅** benar, **❌** salah, **⏳** belum dijawab.
8. Klik **Mulai Kuis 🚀** pada kartu yang dipilih.
9. Jika daftar kosong, ubah filter atau klik **← Kembali ke Dashboard**.

![Gambar 3.21 - Daftar Soal siswa, penanda: (1) dropdown Kelas dan Kategori, (2) tombol 🔍 Cari Soal, (3) tombol Mulai Kuis 🚀](screenshots/3-21-student-list.png)

**Hasil:** tampil 12 soal per halaman beserta pertanyaan, kelas, dan kategori.

### Fitur 3.5.3 — Menjawab kuis siswa (`/student/soal/{id}/quiz`)

**Tujuan:** mengirim satu jawaban dan melihat benar/salah seketika.

**Langkah penggunaan:**

1. Baca panel info kelas, kategori, nama guru, dan **Nomor Soal**.
2. Baca **Pertanyaan untukmu**.
3. Klik satu baris opsi **A**, **B**, **C**, atau **D** hingga muncul tanda **✓**.
4. Klik **✓ Jawab Soal**.
5. Tunggu indikator **Mengirim...**.
6. Baca popup hasil: **Jawaban Benar! 🎉** atau **Jawaban Salah! Coba Lagi! 📚** beserta kunci jawaban.
7. Klik **Soal Berikutnya →** untuk lanjut atau **📊 Lihat Hasil** untuk rekap.
8. Untuk ganti soal, klik **← Pilih Soal Lain**.

![Gambar 3.22 - Kuis siswa, penanda: (1) opsi jawaban A–D, (2) tombol ✓ Jawab Soal, (3) popup hasil](screenshots/3-22-student-quiz.png)

**Hasil:** jawaban tersimpan (satu baris per pasangan siswa–soal; jawaban baru menimpa jawaban lama). Jika sudah pernah menjawab, tampil panel **Jawaban sebelumnya**.

### Fitur 3.5.4 — Melihat Hasil siswa (`/student/result`)

**Tujuan:** melihat nilai dan riwayat jawaban dengan filter.

**Langkah penggunaan:**

1. Di navbar siswa, klik **📊 Hasil**.
2. Baca lingkaran **Nilai Kamu** dan kartu **Benar ✅**, **Salah ❌**, **Total 📚**.
3. Baca judul apresiasi: **Luar Biasa! 🌟** (≥80%), **Bagus Sekali! 👍** (≥60%), **Terus Semangat! 💪** (≥40%), atau **Jangan Menyerah! 🚀** (<40%).
4. Untuk menyaring riwayat, pilih **🏫 Kelas** dan **📚 Kategori**.
5. Klik **🔍 Filter**.
6. Baca tiap baris **Riwayat Jawabanmu** berikon **✅**/**❌** beserta pilihan dan kunci jawaban.
7. Klik **← Kerjakan Soal Lagi** untuk lanjut atau **🏠 Ke Dashboard** untuk kembali.

![Gambar 3.23 - Hasil siswa, penanda: (1) lingkaran Nilai Kamu, (2) tombol 🔍 Filter, (3) tombol ← Kerjakan Soal Lagi](screenshots/3-23-student-hasil.png)

**Hasil:** nilai dihitung dari `benar / total terjawab × 100%`. Jika belum ada jawaban, tampil **Belum Ada Jawaban**.

---

## 3.6 Logout

### Fitur 3.6.1 — Keluar dari aplikasi

**Tujuan:** mengakhiri sesi login agar akun tidak disalahgunakan di perangkat bersama.

**Langkah penggunaan:**

1. Perhatikan navbar atas saat login.
2. Klik tombol ikon keluar (ikon pintu panah merah) di sisi kanan navbar.
3. Pada perangkat kecil, buka menu hamburger terlebih dahulu lalu klik tombol keluar.

**Hasil:** Anda diarahkan ke halaman utama `/` dengan pesan `Logout berhasil`. Tombol ini memakai metode `POST /logout` sehingga tidak dapat diakses hanya dengan mengetik alamat.
