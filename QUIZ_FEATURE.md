# 🎓 Fitur Quiz - Panduan Lengkap

## Overview
Fitur Quiz memungkinkan siswa untuk menjawab soal yang dibuat oleh guru dengan sistem scoring otomatis dan tampilan yang menarik untuk anak-anak SD.

## Fitur Utama

### 1. Dashboard Siswa
**URL:** `/student/dashboard`

Menampilkan:
- ✨ Greeting personal dengan nama siswa
- 📊 Statistik: Jumlah soal terjawab, jawaban benar, dan nilai rata-rata
- 📚 Daftar kategori soal untuk dipilih
- 🏫 Daftar kelas untuk dipilih
- 🚀 Quick action buttons

**Design:**
- Gradient background purple-blue
- Cards dengan gold border
- Emoji untuk setiap elemen
- Smooth hover animations

---

### 2. Daftar Soal
**URL:** `/student/soal`

Fitur:
- 🔍 Filter berdasarkan kelas dan kategori
- 📋 Grid display soal dengan card design
- ✅ Status indicator (sudah dijawab/belum/salah/benar)
- 📝 Judul soal yang terpotong dengan elipsis
- 🚀 Tombol "Mulai Kuis" untuk setiap soal

**Pagination:**
- Default 12 soal per halaman
- Pagination links untuk navigasi

---

### 3. Interface Quiz
**URL:** `/student/soal/{id}/quiz`

Komponen:
- 📚 Header: Nama guru dan nomor soal
- ❓ Pertanyaan soal yang jelas dan besar
- 🔘 4 pilihan jawaban (A, B, C, D)
  - Label dengan background gradient
  - Hover effect dengan shadow
  - Selected state dengan gold border
- ✓ Tombol submit (disabled sampai pilihan dibuat)

**Interaktif:**
- AJAX submission (tidak reload page)
- Loading indicator saat mengirim
- Modal popup untuk hasil

**Previous Answer:**
- Jika siswa sudah pernah menjawab soal ini, akan ditampilkan alert dengan jawaban sebelumnya

---

### 4. Result Modal
**Tampil setelah submit jawaban**

**Jika Jawaban Benar:**
- 🎉 Emoji celebration
- "Jawaban Benar! 🎉"
- Message positif & motivasi
- Informasi pilihan yang dipilih

**Jika Jawaban Salah:**
- 💪 Emoji motivasi
- "Jawaban Salah! Coba Lagi! 📚"
- Informasi pilihan yang dipilih
- Tampil jawaban yang benar
- Pesan motivasi

**Tombol:**
- "← Soal Berikutnya" - Kembali ke list soal
- "📊 Lihat Hasil" - Lihat halaman hasil

---

### 5. Halaman Hasil (Score)
**URL:** `/student/result`

Menampilkan:
- 🏆 Score besar (persentase)
- 📊 Stat cards: Total soal, Benar, Salah
- 🎖️ Achievement badge berdasarkan score:
  - **80%+**: "Luar Biasa! Prestasi Sempurna! 🌟"
  - **60-79%**: "Bagus! Hasil yang Memuaskan!"
  - **40-59%**: "Lumayan! Ada Peningkatan!"
  - **<40%**: "Jangan Menyerah! Coba Lagi Nanti!"

**Riwayat Jawaban:**
- List semua jawaban siswa
- Per jawaban menampilkan:
  - Pertanyaan soal
  - Pilihan yang dipilih (dengan highlight)
  - Jawaban yang benar (jika salah)
  - Status (✅ atau ❌)
  - Kelas & kategori soal

**Filter:**
- Filter by kelas
- Filter by kategori
- Tombol "Filter" untuk apply

**Action Buttons:**
- "← Kembali ke Soal" - Lanjut menjawab
- "🏠 Ke Dashboard" - Kembali dashboard

---

## User Flow

### Siswa Baru
```
1. Login (budi@student.local)
2. Lihat Dashboard → Pilih kategori/kelas
3. Lihat list soal → Klik "Mulai Kuis"
4. Jawab soal → Submit
5. Lihat result modal → Lanjut ke soal berikutnya atau lihat hasil
```

### Lihat Riwayat
```
1. Di manapun → Klik "Lihat Hasil"
2. Lihat semua jawaban & score
3. Bisa filter & re-answer soal
```

---

## Database Schema

### Tabel `jawaban`
```sql
- id (bigint, PK)
- user_id (bigint, FK to users)
- soal_id (bigint, FK to soal)
- jawaban_dipilih (char(1)) - a, b, c, d
- is_correct (boolean)
- waktu_mulai (timestamp, nullable)
- waktu_selesai (timestamp, nullable)
- created_at, updated_at
- Unique: (user_id, soal_id)
```

---

## Design Specifications

### Colors
- **Primary Gradient**: #667eea → #764ba2 (purple)
- **Secondary Gradient**: #00f2fe → #4facfe (cyan)
- **Success**: #4caf50 (green)
- **Error**: #f44336 (red)
- **Gold**: #FFD700

### Font
- **Main Font**: "Comic Sans MS", "Trebuchet MS", cursive
- Membuat kesan playful & fun untuk anak-anak

### Responsive
- Mobile-first design
- Grid: auto-fill dengan minmax 150-280px
- Touch-friendly buttons (min 44px)

---

## Admin/Guru Notes
- Siswa TIDAK bisa share/copy soal
- Setiap jawaban tersimpan otomatis
- Score dihitung otomatis berdasarkan key jawaban
- Siswa bisa re-answer soal untuk improve score
- Tracking lengkap: waktu, jawaban, status

---

## Future Enhancements (Optional)
- Leaderboard per kelas
- Timer per soal
- Analisis kesalahan umum
- Soal random mode
- Difficulty indicator
- Hint system
- Export hasil ke PDF

---

Generated: 2026-07-25
Last Updated: Quiz Feature v1.0
