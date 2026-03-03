<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasUuids;

class Hall extends Model
{
    use HasUuids;
    protected $fillable = ['cinema_id', 'name', 'type'];

    public $searchable = ['name'];

    /**
     * Studio ini milik bioskop mana?
     */
    public function cinema(): BelongsTo
    {
        return $this->belongsTo(Cinema::class);
    }

    /**
     * Mengambil daftar semua kursi yang ada di studio ini.
     */
    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }

    /**
     * Jadwal tayang yang ada di studio ini.
     */
    public function showtimes(): HasMany
    {
        return $this->hasMany(Showtime::class);
    }
}
