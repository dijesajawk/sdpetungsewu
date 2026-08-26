# 🎓 PANDUAN CEPAT - GURU MENJALANKAN QUIZ UNTUK MURID SD

## Situasi Masalah
- Murid SD Kelas 1-2 **tidak boleh bawa HP** ke sekolah
- Quiz di kelas dilakukan di **satu perangkat/proyektor** bersama
- Guru perlu cara **mudah** untuk menjalankan soal tanpa murid login

## ✅ Solusi: Sistem Quiz Tanpa Login

### STEP 1️⃣: Guru Login
```
1. Buka: http://localhost/login
2. Masukkan email + password guru
3. Klik "Login"
```

### STEP 2️⃣: Lihat Daftar Soal
```
1. Di menu, klik "Daftar Soal" atau buka /guru/soal
2. Lihat semua soal yang sudah dibuat
```

### STEP 3️⃣: Jalankan Soal
```
1. Pilih soal yang ingin dijadikan quiz
2. Klik tombol HIJAU "▶ Jalankan" (di bagian kanan soal)
3. Halaman akan menampilkan:
   ✅ KODE AKSES (misal: ABC123)
   🔗 LINK AKSES
   📊 Tombol "Lihat Hasil Jawaban Murid"
```

### STEP 4️⃣: Bagikan Kode/Link ke Murid
Pilih salah satu:

**Opsi A: Tulis Kode di Papan Tulis**
```
Kode: ABC123

Murid buka di laptop/tablet:
http://localhost/quiz/ABC123/name
↓
Input nama → Jawab soal → Lihat hasil
```

**Opsi B: Baca Kode Keras-keras**
```
Guru: "Kode quiz hari ini adalah: A-B-C-1-2-3"
Murid: Input kode tersebut
```

### STEP 5️⃣: Monitor Hasil Murid (Real-time)
```
1. Guru buka tab baru → klik "Lihat Hasil Jawaban Murid"
2. Lihat statistik:
   - Total murid menjawab
   - Berapa yang benar
   - Persentase nilai
3. Lihat daftar nama murid + jawaban mereka
```

### STEP 6️⃣: Tutup Sesi
```
Setelah semua murid selesai:
1. Klik tombol MERAH "✕ Tutup Sesi"
2. Sesi ditutup, murid sudah tidak bisa jawab lagi
```

---

## 📱 DARI SISI MURID

Murid tidak perlu login, cukup:

```
1. Buka link atau masukkan kode
   ↓
2. Input nama lengkap
   ↓
3. Baca soal & pilih jawaban (A/B/C/D)
   ↓
4. Klik "Kirim Jawaban"
   ↓
5. Lihat hasil (Benar/Salah) + jawaban yang benar
```

---

## 🎯 Keuntungan Sistem Ini

✅ **Murid tidak perlu akun** - hanya input nama  
✅ **Guru kontrol penuh** - bisa buka/tutup kapan saja  
✅ **Real-time monitoring** - lihat hasil langsung  
✅ **Cocok untuk kelas** - satu soal untuk banyak murid  
✅ **Aman** - guru yang bisa buat session, murid hanya akses  

---

## ⚙️ Troubleshooting

**Q: Murid tidak bisa akses soal?**
A: Periksa:
- Kode benar (case-insensitive, misal: abc123 = ABC123)
- Sesi belum ditutup oleh guru
- URL: `/quiz/{KODE}/name`

**Q: Jawaban murid tidak tersimpan?**
A: Cek:
- Koneksi internet stabil
- Guru belum tutup sesi
- Browser tidak ada error (lihat console)

**Q: Guru ingin lihat hasil tapi murid masih menjawab?**
A: Tidak masalah, buka tab baru dan refresh halaman hasil, akan update otomatis

---

## 📞 Bantuan Lebih Lanjut

Lihat file: `SISTEM_QUIZ_TANPA_LOGIN.md` untuk dokumentasi lengkap teknis
