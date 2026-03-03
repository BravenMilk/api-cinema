<?php

namespace App\Repositories;

use App\Models\SeatType;

class SeatTypeRepository extends BaseRepository
{
    public function __construct(SeatType $model)
    {
        parent::__construct($model);
    }
}
