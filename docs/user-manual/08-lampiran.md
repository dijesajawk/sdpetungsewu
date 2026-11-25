# LAMPIRAN

## A. Daftar Screenshot yang Harus Diambil

Ambil screenshot pada resolusi desktop 1366×768 dan HP 360×800 bila memungkinkan. Tandai bagian sesuai nomor penanda.

| File | Halaman / Rute | Bagian yang harus ditandai |
|---|---|---|
| `screenshots/logo-sekolah.png` | Logo `public/images/logo-sekolah.jpg` | (1) Logo resmi yang dipakai di navbar |
| `screenshots/3-01-halaman-utama.png` | `/` (belum login) | (1) Logo Web Edukasi, (2) tombol **Login**, (3) tombol **Daftar Gratis ✨** |
| `screenshots/3-02-register-guru.png` | `/register` | (1) **Pilih Foto →**, (2) field Nama dan Email, (3) tombol **Buat Akun ✨** |
| `screenshots/3-03-login.png` | `/login` | (1) field Email, (2) field Password, (3) tombol **Masuk Sekarang →** |
| `screenshots/3-04-dashboard-guru.png` | `/guru/dashboard` | (1) **＋ Tambah Soal**, (2) **Quiziz — Atur & Mulai**, (3) **Lihat Semua Soal Saya →** |
| `screenshots/3-05-buat-soal.png` | `/guru/soal/create` | (1) Pilih Kelas dan Mata Pelajaran, (2) Pertanyaan dan A–D, (3) **Simpan Soal** |
| `screenshots/3-06-daftar-soal.png` | `/guru/soal` | (1) Mata Pelajaran, (2) **Filter**, (3) **▶ Jalankan** |
| `screenshots/3-07-edit-soal.png` | `/guru/soal/{id}/edit` | (1) Field yang diubah, (2) **Perbarui Soal** |
| `screenshots/3-08-scan-upload.png` | `/guru/soal/scan/upload` | (1) Area upload gambar, (2) Pilih Kelas, (3) **Proses & Scan** |
| `screenshots/3-09-scan-review.png` | `/guru/soal/scan/review` | (1) Gambar Asli, (2) Pertanyaan dan Jawaban Benar, (3) **💾 Simpan Semua Soal** |
| `screenshots/3-10-upload-file.png` | `/guru/soal/upload/file` | (1) Area upload file, (2) Mata Pelajaran, (3) **📤 Upload & Proses** |
| `screenshots/3-11-upload-review.png` | `/guru/soal/upload/review` | (1) Informasi File, (2) Teks Hasil Ekstrak, (3) **💾 Simpan Semua Soal** |
| `screenshots/3-12-mulai-soal.png` | `/guru/quiz/start` | (1) 🟢–🎲, (2) **🏫 Pilih Kelas**, (3) **▶ Mulai Session (Buat Session)** |
| `screenshots/3-13-display-session.png` | `/guru/quiz/{session}/display` | (1) Kode Akses Murid, (2) **📋 Salin Link**, (3) **📊 Lihat Hasil Jawaban Murid (Real-time)** |
| `screenshots/3-14-session-info.png` | `/guru/quiz/{session}/info` | (1) Total Jawab dan Persentase, (2) tabel Jawaban Murid, (3) **✕ Tutup Session** |
| `screenshots/3-15-immediate.png` | `/guru/quiz/immediate` | (1) Pilihan A–D, (2) **Jawab**, (3) **Acak Ulang** dan **Keluar** |
| `screenshots/3-16-admin-dashboard.png` | `/admin/dashboard` | (1) Nama dan email guru, (2) **✓ Verifikasi**, (3) **✕ Tolak** |
| `screenshots/3-17-quiz-nama.png` | `/quiz/{kode}/name` | (1) Jumlah soal, (2) Nama lengkap, (3) **Mulai Quiz →** |
| `screenshots/3-18-quiz-jawab.png` | `/quiz/{kode}?nama=...` | (1) Progres Soal N dari Total, (2) pilihan A–D, (3) **Berikutnya →** |
| `screenshots/3-19-quiz-hasil.png` | `/quiz/{kode}/result` | (1) kartu Skor, (2) Detail Jawaban, (3) label ✓/✕ |
| `screenshots/3-20-student-dashboard.png` | `/student/dashboard` | (1) **➡️ Lihat Semua Soal**, (2) **📊 Lihat Hasil**, (3) **Mulai →** |
| `screenshots/3-21-student-list.png` | `/student/soal` | (1) Kelas dan Kategori, (2) **🔍 Cari Soal**, (3) **Mulai Kuis 🚀** |
| `screenshots/3-22-student-quiz.png` | `/student/soal/{id}/quiz` | (1) Opsi A–D, (2) **✓ Jawab Soal**, (3) popup hasil |
| `screenshots/3-23-student-hasil.png` | `/student/result` | (1) Nilai Kamu, (2) **🔍 Filter**, (3) **← Kerjakan Soal Lagi** |

## B. Contoh Output dan Laporan

### B.1 Kode sesi kuis guru

- Kode 6 karakter huruf/angka kapital, contoh: `A1B2C3` (dibangkitkan otomatis oleh `SoalSession::generateSessionCode`).
- Link: `[ISI MANUAL: basis URL]/quiz/A1B2C3/name`.
- Isi halaman sesi: Total Soal, Tingkat Kesulitan, Kode Akses Murid, Link Akses, Instruksi untuk Murid, Status **🟢 AKTIF**/**✖ SELESAI**.

### B.2 Rekap hasil murid (guru)

- Kartu: Total Soal, Total Jawab, Jawab Benar, Persentase Benar.
- Podium 5 besar: nama, jumlah benar, persentase.
- Tabel per soal: No, Nama Murid, Jawaban (huruf + teks), Status **✓ Benar**/**✕ Salah**.

### B.3 Hasil murid tanpa login

- Kartu: Total Soal, Terjawab, Benar, Skor (%).
- Detail per soal: label **✓ Benar**/**✕ Salah**, pilihan murid, dan kunci jawaban bila salah.

### B.4 Hasil siswa login

- Lingkaran **Nilai Kamu** (%), kartu Benar/Salah/Total, dan **Riwayat Jawabanmu** yang dapat difilter kelas dan kategori.

### B.5 Pesan sistem yang dikutip

- `Registrasi berhasil! Silakan tunggu Admin memverifikasi akun Anda. Cek email Anda untuk informasi lebih lanjut.`
- `Logout berhasil`
- `Soal berhasil ditambahkan`
- `Soal berhasil diperbarui`
- `Soal berhasil dihapus`
- `N soal berhasil ditambahkan dari scan!`
- `N soal berhasil ditambahkan dari file upload!`
- `Quiz telah dimulai`
- `Sesi soal telah ditutup`
- `Benar! Lanjut ke soal berikutnya.`
- `Jawaban salah. Coba lagi.`

## C. Kontak Support

| Kebutuhan | Kontak |
|---|---|
| Admin aplikasi | [ISI MANUAL: nama, WA/telepon, email] |
| Operator sekolah | [ISI MANUAL: nama, WA/telepon, email] |
| Jam layanan | [ISI MANUAL: hari dan jam layanan] |
| Eskalasi teknis | [ISI MANUAL: prosedur bila database/server bermasalah] |

> Saat melapor, sertakan: peran akun, URL halaman, pesan error persis, kode sesi (jika kuis), dan waktu kejadian.
