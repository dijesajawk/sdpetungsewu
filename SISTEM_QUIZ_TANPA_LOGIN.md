# REVISI SISTEM - Quiz Tanpa Login untuk Murid

## 📋 Ringkasan Perubahan

Sistem telah direvisi sehingga:
- **Hanya Guru yang Login** dengan akun mereka
- **Guru dapat Menjalankan Soal** dari dashboard dengan tombol "▶ Jalankan"
- **Murid Tidak Perlu Login** - akses soal langsung melalui kode/link yang diberikan guru
- **Cocok untuk SD Kelas 1-2** yang tidak boleh membawa HP

---

## 🔄 ALUR KERJA SISTEM BARU

### 1️⃣ GURU: Login & Lihat Daftar Soal
```
LOGIN (Guru)
↓
Dashboard Guru
↓
Lihat Daftar Soal (dengan tombol "▶ Jalankan")
```

### 2️⃣ GURU: Jalankan Soal (Buat Session)
- Klik tombol **"▶ Jalankan"** pada soal yang ingin dijalankan
- Sistem membuat session baru dengan kode unik (misal: "ABC123")
- Guru mendapat:
  - ✅ Kode Akses (untuk diceritakan/ditulis di papan tulis)
  - 🔗 Link Akses (bisa di-share jika ada media)
  - 📊 Tombol "Lihat Hasil Jawaban Murid" untuk monitoring

### 3️⃣ MURID: Akses Soal (Tanpa Login)
```
Murid masukkan KODE / buka LINK
↓
Input Nama Murid
↓
Lihat Soal & Pilihan Jawaban
↓
Kirim Jawaban
↓
Lihat Hasil (Benar/Salah + Jawaban Benar)
```

### 4️⃣ GURU: Monitoring Hasil Murid
- Klik **"📊 Lihat Hasil Jawaban Murid"** dari halaman session
- Lihat:
  - Statistik: Total Jawab, Benar, Persentase
  - Daftar nama murid + jawaban + status (Benar/Salah)
- Klik **"✕ Tutup Sesi"** untuk menutup sesi

---

## 📁 FILE YANG DITAMBAH/DIUBAH

### Database
- **Migration**: `2026_07_25_120000_add_session_support_for_quiz.php`
  - Tabel baru: `soal_sessions` (track session soal guru)
  - Kolom baru di `jawaban`: `session_id`, `nama_murid`, `identifier`

### Models
- **Baru**: `SoalSession.php` - model untuk session soal
- **Update**: `Jawaban.php` - tambah relasi ke SoalSession

### Controllers
- **Baru**: `QuizController.php` - handle quiz publik (murid tanpa login)
- **Update**: `GuruController.php` - tambah method:
  - `startSoal()` - buat session baru
  - `sessionInfo()` - lihat hasil jawaban
  - `endSession()` - tutup sesi

### Routes
- **Update**: `routes/web.php`
  - Tambah route group `/quiz` (publik)
  - Tambah route di guru prefix

### Views
- **Baru**:
  - `resources/views/guru/soal/start-quiz.blade.php` - tampilkan kode/link
  - `resources/views/guru/soal/session-info.blade.php` - hasil jawaban murid
  - `resources/views/quiz/name-input.blade.php` - input nama murid
  - `resources/views/quiz/student-quiz.blade.php` - tampilkan soal
  - `resources/views/quiz/result.blade.php` - hasil jawaban murid
  - `resources/views/quiz/session-ended.blade.php` - sesi ditutup
- **Update**: `resources/views/guru/soal/list.blade.php` - tambah tombol "▶ Jalankan"

---

## 🎯 FLOW TEKNIS

### Murid Input Nama & Jawab Soal
1. Murid buka: `/quiz/{sessionCode}/name`
2. Input nama → redirect ke `/quiz/{sessionCode}?nama=...`
3. Lihat soal & jawab → POST ke `/quiz/{sessionCode}/submit`
4. Jawaban disimpan dengan:
   - `session_id`: SoalSession ID
   - `nama_murid`: Nama yang diinput
   - `identifier`: UUID dari session browser murid
5. Redirect ke `/quiz/{sessionCode}/result`

### Jawaban Disimpan
- Tidak perlu `user_id` (karena murid tidak login)
- Menggunakan `session_id` + `identifier` untuk tracking
- Satu murid = satu `identifier` (per device/session browser)

### Guru Monitoring
- Klik "Lihat Hasil" → GET `/guru/session/{sessionId}/info`
- Lihat statistik + tabel jawaban semua murid
- Bisa tutup sesi → POST `/guru/session/{sessionId}/end`

---

## 📝 CATATAN PENTING

1. **Identitas Murid**: Hanya berdasarkan nama input + identifier session browser
   - Jika murid membuka di device baru, dihitung sebagai murid baru
   - Jika jawaban lagi di device yang sama, jawaban akan di-update

2. **Keamanan**: 
   - Session code adalah 6 karakter random unik
   - Tidak ada autentikasi untuk akses soal (tujuannya agar murid SD bisa akses)
   - Guru bisa tutup sesi kapan saja untuk mencegah akses lanjutan

3. **Jawaban Ganda**:
   - Murid bisa jawab berkali-kali di soal yang sama
   - Jawaban terakhir akan disimpan
   - Status benar/salah ditampilkan langsung

4. **Database**:
   - Jawaban tetap tersimpan dengan `user_id = NULL` untuk guest responses
   - Dapat di-filter/dilihat guru anytime

---

## 🚀 CARA MENGGUNAKAN

### Guru:
1. Login dengan akun guru
2. Buka "Daftar Soal"
3. Klik tombol **"▶ Jalankan"** pada soal pilihan
4. Copy/Share kode atau link ke murid
5. Monitor hasil di tab "Lihat Hasil Jawaban Murid"

### Murid:
1. Buka link atau masukkan kode di halaman `/quiz`
2. Masukkan nama
3. Jawab soal
4. Lihat hasil

---

## ✅ FITUR YANG SUDAH BERJALAN

- ✅ Guru login & lihat soal
- ✅ Guru jalankan soal (buat session)
- ✅ Murid akses soal tanpa login
- ✅ Murid input nama & jawab
- ✅ Lihat hasil jawaban (benar/salah)
- ✅ Guru monitor hasil murid
- ✅ Guru tutup session

---

## 🔄 MIGRASI DATA LAMA (OPSIONAL)

Jika ada data jawaban student lama dari login, tidak perlu dimigrasi. Sistem baru ini fokus pada murid tanpa login untuk SD Kelas 1-2.

---

**Dibuat**: 2026-07-25
**Status**: ✅ SIAP PAKAI
