# BAB IV TROUBLESHOOTING

Tabel 4.1 — Masalah, penyebab berdasarkan kode, dan solusi. Kutipan pesan ditulis persis seperti di aplikasi.

| No. | Masalah | Penyebab (berdasarkan kode) | Solusi |
|---|---|---|---|
| 1 | Tidak bisa login; tampil `Email atau password salah.` | Kredensial salah, atau akun belum `verified` sehingga `Auth::attempt` dengan `status = verified` gagal (`AuthController.php`) | Periksa ejaan email dan password (minimal 6 karakter); pastikan Caps Lock mati; jika akun guru baru, minta admin memverifikasi dahulu |
| 2 | Setelah login tampil `Akun Anda belum diverifikasi oleh admin. Silakan tunggu.` | Status akun masih `pending` (atau `rejected`) | Tunggu verifikasi admin pada **Dashboard Admin**; jangan membuat akun ganda |
| 3 | Dibuka halaman guru/admin lalu kembali ke login dengan `Unauthorized. Hanya guru verified yang dapat akses.` | Peran bukan guru atau status belum `verified` (`GuruMiddleware.php`) | Login dengan akun guru yang sudah **AKTIF ✓**; siswa gunakan menu siswa atau jalur murid tanpa login |
| 4 | Dibuka halaman admin lalu kembali ke login dengan `Unauthorized. Hanya admin yang dapat akses.` | Peran bukan admin (`AdminMiddleware.php`) | Login dengan akun `role = admin`; guru tidak dapat membuka `/admin/*` |
| 5 | Registrasi gagal; ada pesan di bawah Foto/Nama/Email/Password | Melanggar validasi: foto wajib gambar `jpeg,png,jpg,gif` maks 2MB; nama wajib; email wajib unik; password minimal 6 dan harus sama dengan konfirmasi | Ganti foto yang lebih kecil/berformat benar; gunakan email lain; samakan **Password** dan **Ulangi Password** |
| 6 | Tombol **Buat Akun ✨** tidak merespons | Berkas foto belum dipilih atau koneksi lambat; skrip menonaktifkan tombol saat mengirim | Tunggu hingga selesai; pastikan foto sudah dipilih karena field foto wajib |
| 7 | Formulir soal tidak tersimpan; ada pesan di bawah field | Melanggar validasi `storeSoal`: kelas wajib ada; mata pelajaran harus 9 pilihan resmi; tingkat harus mudah/normal/sulit; pertanyaan minimal 10 karakter; A–D dan jawaban benar wajib | Lengkapi semua dropdown; tulis pertanyaan ≥10 karakter; isi A–D; pilih jawaban benar |
| 8 | Tampil `Anda tidak memiliki akses ke soal ini` saat edit/hapus/update | Soal milik guru lain (`user_id` berbeda) | Hanya ubah soal yang Anda buat; minta pemilik soal yang mengubah |
| 9 | Tampil `Tidak ada soal dengan filter yang dipilih` saat mulai kuis | Tidak ada soal milik Anda yang cocok dengan kombinasi kesulitan + kelas | Ubah ke **🎲 Campuran** atau **-- Semua Kelas --**; tambah soal dahulu |
| 10 | Tampil `Data tidak valid` saat verifikasi | Akun bukan guru atau status bukan `pending` (`AdminController.php`) | Muat ulang dashboard; pastikan yang diklik adalah guru pending |
| 11 | Murid tidak bisa masuk; kode benar tetapi sesi berakhir | Sesi sudah `completed`/melewati `selesai_at` (`SoalSession::isActive`) | Minta guru membuat sesi baru; jangan gunakan kode lama |
| 12 | Murid diarahkan terus ke input nama | Parameter `nama` kosong dan sesi browser tidak menyimpan `quiz_nama_murid` | Isi **Nama lengkap** lalu klik **Mulai Quiz →**; jangan gunakan mode privat yang menghapus sesi |
| 13 | Jawaban murid tidak tersimpan / tampil error sesi ditutup | Sesi sudah ditutup guru atau `soal_id`/`jawaban_dipilih` tidak valid | Minta guru membuka sesi baru; pilih hanya A–D; periksa koneksi |
| 14 | Scan menampilkan `Tidak ada soal yang terdeteksi.` | Foto blur/miring/terpotong atau format tidak seperti `1. Pertanyaan?` + `A) Jawaban` | Foto ulang dengan terang dan lurus; gunakan format bernomor; jika gagal, klik **✏️ Input Manual** |
| 15 | Upload file menampilkan `Tidak dapat mengekstrak teks dari file yang dipilih.` | PDF berisi pindaian gambar (bukan teks), terenkripsi, atau format tidak didukung | Untuk PDF pindaian gunakan **Scan Soal**; untuk DOC lama pastikan `catdoc` tersedia di server; coba PDF teks biasa |
| 16 | Review scan/upload kosong hanya menampilkan spinner | Skrip parsing di browser tidak menemukan pola soal atau CDN Tesseract.js diblokir | Periksa koneksi ke CDN; sederhanakan format soal; gunakan **Upload Ulang**/**Scan Ulang** |
| 17 | Tidak ada tombol export/cetak laporan | Fitur export/cetak belum ada di `routes/web.php` dan views | [ISI MANUAL: prosedur resmi saat ini, misalnya salin manual atau hubungi admin] |
| 18 | Tidak ada menu profil dan reset password | Tidak ditemukan rute/form profil dan lupa password; hanya ada tabel `password_reset_tokens` bawaan | [ISI MANUAL: prosedur resmi perubahan data dan reset password melalui admin] |
| 19 | Error database/koneksi setelah instalasi | Konfigurasi `.env` belum benar atau migrasi/seeder belum dijalankan | Sesuaikan `DB_*` di `.env`; jalankan `php artisan migrate:fresh --seed`; lalu `php artisan config:clear` dan `php artisan cache:clear` |
| 20 | Perubahan kode tidak tampil / rute tidak ditemukan | Cache konfigurasi/rute lama | Jalankan `php artisan route:clear`, `php artisan cache:clear`, dan `npm run build` bila mengubah aset Vite |

> Jika masalah berlanjut, catat pesan persis yang tampil, alamat halaman (URL), peran akun, dan waktu kejadian sebelum menghubungi support pada Lampiran C.
