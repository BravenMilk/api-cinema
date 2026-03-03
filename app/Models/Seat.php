<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasUuids;

class Seat extends Model
{
    use HasUuids;
    protected $fillable = [
        'hall_id',
        'seat_type_id',
        'row_label',
        'seat_number',
        'pos_x',
        'pos_y',
        'is_active'
    ];

    public $searchable = ['row_label', 'seat_number'];

    /**
     * Kursi ini berada di studio (Hall) mana?
     */
    public function hall(): BelongsTo
    {
        return $this->belongsTo(Hall::class);
    }

    /**
     * Kursi ini tipe apa (Regular/VIP)?
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(SeatType::class, 'seat_type_id');
    }

    /**
     * Tiket-tiket yang menggunakan kursi ini
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
