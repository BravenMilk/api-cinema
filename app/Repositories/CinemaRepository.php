<?php

namespace App\Repositories;

use App\Models\Cinema;

class CinemaRepository extends BaseRepository
{
    public function __construct(Cinema $model)
    {
        parent::__construct($model);
    }

    public function getByCity($cityId)
    {
        return $this->model->where('city_id', $cityId)->get();
    }
}
