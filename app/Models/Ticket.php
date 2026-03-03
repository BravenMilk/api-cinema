<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuids;

class Ticket extends Model
{
    use HasUuids;
    protected $fillable = [
        'booking_id',
        'seat_id',
        'ticket_serial',
        'is_scanned',
        'scanned_at'
    ];

    protected $appends = ['qr_code_url'];

    public function getQrCodeUrlAttribute()
    {
        // Cek dulu: Apakah tiket ini punya data Booking yang ter-load?
        // Dan apakah status bookingnya 'paid'?
        if ($this->booking && $this->booking->status === 'paid') {
            return 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . $this->ticket_serial . '&choe=UTF-8';
        }

        // Jika belum bayar atau data booking tidak ada, jangan tampilkan QR Tiket Masuk
        return null;
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Tiket ini berlaku untuk kursi mana?
     */
    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class);
    }
}
