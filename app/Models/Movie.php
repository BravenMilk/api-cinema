<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasUuids;

class Movie extends Model
{
    use HasUuids, HasFactory;
    protected $fillable = [
        'title',
        'description',
        'duration',
        'poster_url',
        'trailer_url',
        'release_date',
        'rating'
    ];

    public $searchable = ['title', 'description'];

    /**
     * Satu film bisa dijadwalkan berkali-kali.
     */
    public function showtimes(): HasMany
    {
        return $this->hasMany(Showtime::class);
    }

    /**
     * Ulasan untuk film ini.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(\App\Models\Review::class);
    }
}
