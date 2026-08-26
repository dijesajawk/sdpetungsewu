<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SoalSessionItem extends Model
{
    use HasFactory;

    protected $table = 'soal_session_items';

    protected $fillable = [
        'session_id',
        'soal_id',
        'urutan',
    ];

    public $timestamps = true;

    /**
     * Get session yang memiliki item ini
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(SoalSession::class, 'session_id');
    }

    /**
     * Get soal
     */
    public function soal(): BelongsTo
    {
        return $this->belongsTo(Soal::class);
    }
}
