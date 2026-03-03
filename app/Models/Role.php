<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasUuids;

class Role extends Model
{
    use HasUuids;
    protected $fillable = ['name'];

    public $searchable = ['name'];

    /**
     * Satu Role (misal: Customer) dimiliki oleh banyak User.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
