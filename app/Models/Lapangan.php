<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    protected $fillable = [
        'nama_lapangan',
        'jenis_lapangan',
        'harga_pagi',
        'harga_malam',
    ];

    /**
     * Relasi: Satu lapangan memiliki banyak booking
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}