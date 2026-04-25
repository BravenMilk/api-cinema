<?php

namespace App\Repositories;

use App\Models\Review;

class ReviewRepository extends BaseRepository
{
    public function __construct(Review $model)
    {
        parent::__construct($model);
    }

    public function getByMovie(string $movieId, array $filters = [])
    {
        $query = $this->model->newQuery()
            ->where('movie_id', $movieId)
            ->with('user:id,name');

        return $query->orderBy('created_at', 'desc')
            ->paginate($filters['limit'] ?? 10);
    }

    public function findByUserAndMovie(string $userId, string $movieId): ?Review
    {
        return $this->model->where('user_id', $userId)
            ->where('movie_id', $movieId)
            ->first();
    }

    public function getAverageRating(string $movieId): float
    {
        return round($this->model->where('movie_id', $movieId)->avg('rating') ?? 0, 1);
    }
}
