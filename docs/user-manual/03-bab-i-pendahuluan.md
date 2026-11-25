# BAB I PENDAHULUAN

## 1.1 Latar Belakang

Web Edukasi adalah aplikasi web berbasis Laravel yang digunakan di SD Negeri 2 Petungsewu, Wagir, Kabupaten Malang. Berdasarkan `README.md`, `PANDUAN_LENGKAP.md`, dan implementasi di kode, aplikasi ini dibuat untuk membantu guru membuat soal dengan lebih cepat dan efisien, menghemat waktu penyusunan soal, dan mengurangi kerja manual.

Pada implementasi aktual di kode (`routes/web.php`, `GuruController.php`, `QuizController.php`), kebutuhan tersebut diwujudkan melalui:

- Bank soal per guru dengan tingkat kesulitan dan mata pelajaran.
- Input soal manual satu per satu.
- Import soal dari foto hasil pindaian (Scan Soal / OCR di browser dengan Tesseract.js).
- Import soal dari berkas PDF, DOC, dan DOCX.
- Pelaksanaan kuis berbasis sesi dengan kode akses 6 karakter untuk murid tanpa login.
- Mode tampil langsung (immediate) untuk latihan guru di kelas.
- Verifikasi akun guru oleh admin sebelum akun dapat digunakan.
- Modul siswa login (`StudentController.php`) yang masih tersedia di kode untuk menjawab soal dan melihat nilai.

## 1.2 Tujuan

1. Menyediakan bank soal digital yang terorganisasi berdasarkan kelas, mata pelajaran, dan tingkat kesulitan.
2. Mempermudah guru menambah soal melalui formulir manual, pindaian foto, dan unggahan berkas.
3. Memungkinkan pelaksanaan kuis kelas tanpa mengharuskan setiap murid memiliki akun.
4. Memastikan hanya guru terverifikasi yang dapat mengelola soal dan menjalankan kuis.
5. Menyediakan rekap jawaban murid secara real-time untuk guru.

## 1.3 Manfaat

1. Guru tidak perlu menyusun ulang soal yang sama setiap semester.
2. Guru dapat memanfaatkan soal cetak yang sudah ada melalui foto atau berkas.
3. Murid kelas rendah (misalnya Kelas 1–2 SD) dapat mengikuti kuis hanya dengan memasukkan nama, tanpa mengingat email dan password.
4. Admin dapat menjaga kualitas pengguna melalui proses verifikasi guru.
5. Hasil kuis tersimpan otomatis sehingga guru dapat memantau perolehan jawaban benar dan salah.

## 1.4 Pengguna Aplikasi

| No. | Pengguna | Syarat akses | Ringkasan tugas |
|---|---|---|---|
| 1 | Pengunjung (belum login) | Tidak perlu akun | Membaca halaman utama, menuju halaman **Login** dan **Daftar sebagai Guru**, atau membuka link kuis publik dari guru |
| 2 | Guru | Daftar, lalu status `verified` oleh admin | Membuat dan mengelola soal, menjalankan kuis sesi dan kuis langsung, memantau hasil murid |
| 3 | Admin | Akun dengan `role = admin` | Memverifikasi atau menolak pendaftaran guru |
| 4 | Murid tanpa login | Memiliki kode sesi 6 karakter atau link dari guru | Memasukkan nama, menjawab soal, melihat hasil |
| 5 | Siswa dengan akun login | Akun dengan `role = student` (modul masih ada di kode) | Melihat dashboard, menjawab soal, melihat hasil |

> Catatan: pendaftaran melalui halaman **Daftar Akun Guru** selalu membuat akun dengan peran guru dan status `pending` (`AuthController.php`). Akun admin dan akun siswa tidak dapat dibuat dari halaman pendaftaran.
