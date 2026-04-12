<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    // Konstanta status booking
    const STATUS_PENDING   = 'pending';
    const STATUS_APPROVED  = 'approved';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'lapangan_id',
        'nama_pelanggan',
        'no_hp',
        'tanggal',
        'jam',
        'harga',
        'status',
        'snap_token',
        'payment_status',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    /**
     * Relasi: Booking milik satu lapangan
     */
    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }

    /**
     * Scope: hanya booking yang approved
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope: hanya booking yang pending
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope: booking yang aktif (pending + approved) — dianggap mengisi slot
     */
    public function scopeAktif($query)
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_APPROVED]);
    }
}