<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasUuids;

class SeatType extends Model
{
    use HasUuids;
    protected $fillable = ['name', 'additional_price'];

    public $searchable = ['name'];

    /**
     * Jenis kursi (misal: VIP) digunakan oleh banyak kursi fisik.
     */
    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }
}
