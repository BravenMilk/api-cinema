<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasUuids;

class Cinema extends Model
{
    use HasUuids, HasFactory;
    protected $fillable = ['city_id', 'name', 'address'];

    public $searchable = ['name', 'address'];

    /**
     * Bioskop ini berada di kota mana?
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Satu bioskop memiliki beberapa studio (Hall).
     */
    public function halls(): HasMany
    {
        return $this->hasMany(Hall::class);
    }
}
