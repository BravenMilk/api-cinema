<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasUuids;

class Booking extends Model
{
    use HasUuids;
    protected $fillable = [
        'booking_code',
        'user_id',
        'showtime_id',
        'total_price',
        'status',
        'payment_limit'
    ];

    public $searchable = ['booking_code'];

    protected $appends = ['payment_url'];

    public function getPaymentUrlAttribute()
    {
        if ($this->status === 'pending') {
            // Simulasi link QRIS / Payment Gateway
            return 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=PAY:' . $this->booking_code . '&choe=UTF-8';
        }
        return null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function showtime(): BelongsTo
    {
        return $this->belongsTo(Showtime::class);
    }

    /**
     * Satu pemesanan berisi detail tiket untuk tiap kursi.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
