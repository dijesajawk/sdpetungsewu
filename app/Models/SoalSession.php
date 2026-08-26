<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SoalSession extends Model
{
    use HasFactory;

    protected $table = 'soal_sessions';

    protected $fillable = [
        'user_id',
        'session_code',
        'kode_akses',
        'mulai_at',
        'selesai_at',
        'status',
        'difficulty_type',
    ];

    protected function casts(): array
    {
        return [
            'mulai_at' => 'datetime',
            'selesai_at' => 'datetime',
        ];
    }

    /**
     * Get guru pemilik session ini
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get semua item soal dalam session ini
     */
    public function items(): HasMany
    {
        return $this->hasMany(SoalSessionItem::class, 'session_id')->orderBy('urutan');
    }

    /**
     * Get semua soal dalam session ini
     */
    public function soal()
    {
        return $this->belongsToMany(Soal::class, 'soal_session_items', 'session_id', 'soal_id')
                    ->orderBy('urutan');
    }

    /**
     * Get semua jawaban dalam session ini
     */
    public function jawaban(): HasMany
    {
        return $this->hasMany(Jawaban::class, 'session_id');
    }

    /**
     * Generate session code unik
     */
    public static function generateSessionCode(): string
    {
        do {
            $code = strtoupper(substr(uniqid(), -6));
        } while (self::where('session_code', $code)->exists());

        return $code;
    }

    /**
     * Get status session
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && (is_null($this->selesai_at) || $this->selesai_at > now());
    }
}
