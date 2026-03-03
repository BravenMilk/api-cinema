<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasUuids;

class Showtime extends Model
{
    use HasUuids;
    protected $fillable = [
        'movie_id',
        'hall_id',
        'start_time',
        'end_time',
        'base_price',
        'booking_start_at',
        'booking_end_at'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'booking_start_at' => 'datetime',
        'booking_end_at' => 'datetime'
    ];

    public $searchable = ['start_time'];

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }

    public function hall(): BelongsTo
    {
        return $this->belongsTo(Hall::class);
    }

    /**
     * Mencatat transaksi yang terjadi pada jadwal tayang ini.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
