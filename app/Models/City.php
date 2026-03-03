<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasUuids;

class City extends Model
{
    use HasUuids, HasFactory;
    protected $fillable = ['name'];

    public $searchable = ['name'];

    /**
     * Satu kota bisa memiliki banyak lokasi bioskop (Cinema).
     */
    public function cinemas(): HasMany
    {
        return $this->hasMany(Cinema::class);
    }
}
