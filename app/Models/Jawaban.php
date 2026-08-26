<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jawaban extends Model
{
    use HasFactory;

    protected $table = 'jawaban';

    protected $fillable = [
        'user_id',
        'soal_id',
        'session_id',
        'nama_murid',
        'identifier',
        'jawaban_dipilih',
        'is_correct',
        'waktu_mulai',
        'waktu_selesai',
    ];

    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
            'waktu_mulai' => 'datetime',
            'waktu_selesai' => 'datetime',
        ];
    }

    /**
     * Get siswa yang membuat jawaban ini (optional, untuk jawaban dari login)
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get soal yang dijawab
     */
    public function soal(): BelongsTo
    {
        return $this->belongsTo(Soal::class);
    }

    /**
     * Get session soal untuk jawaban ini (optional, untuk jawaban guest/murid)
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(SoalSession::class, 'session_id');
    }
}
