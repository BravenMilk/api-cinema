<?php

namespace App\Repositories;

use App\Models\Movie;

class MovieRepository extends BaseRepository
{
    public function __construct(Movie $model)
    {
        parent::__construct($model);
    }

    public function getNowShowingWithShowtimes()
    {
        return $this->model->with(['showtimes.hall.cinema'])
            ->whereHas('showtimes')
            ->get();
    }
}
