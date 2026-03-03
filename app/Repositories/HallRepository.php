<?php

namespace App\Repositories;

use App\Models\Hall;

class HallRepository extends BaseRepository
{
    public function __construct(Hall $model)
    {
        parent::__construct($model);
    }

    public function getByCinema($cinemaId)
    {
        return $this->model->where('cinema_id', $cinemaId)->get();
    }

    public function getWithSeats($id)
    {
        return $this->model->with(['seats.type', 'cinema'])->find($id);
    }
}
