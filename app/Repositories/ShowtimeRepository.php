<?php

namespace App\Repositories;

use App\Models\Showtime;

class ShowtimeRepository extends BaseRepository
{
    public function __construct(Showtime $model)
    {
        parent::__construct($model);
    }

    public function getAllFiltered(array $filters = [], int $perPage = 20)
    {
        $query = $this->model->with(['movie', 'hall.cinema.city']);

        if (!empty($filters['movie_id'])) {
            $query->where('movie_id', $filters['movie_id']);
        }

        if (!empty($filters['city_id'])) {
            $query->whereHas('hall.cinema', function ($q) use ($filters) {
                $q->where('city_id', $filters['city_id']);
            });
        }

        if (!empty($filters['search'])) {
            $query->whereHas('movie', function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->paginate($perPage);
    }

    public function getDetails($id)
    {
        return $this->model->with(['movie', 'hall.cinema', 'hall.seats.type'])->find($id);
    }
}

