<?php

namespace App\Repositories;

use App\Models\Booking;

class BookingRepository extends BaseRepository
{
    public function __construct(Booking $model)
    {
        parent::__construct($model);
    }

    public function getByUser(int $userId)
    {
        return $this->model->where('user_id', $userId)
            // Load tickets.booking agar di dalam Ticket model bisa cek status booking tanpa query ulang (N+1)
            ->with(['showtime.movie', 'tickets.seat.type', 'tickets.booking']) 
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getFiltered(array $filters)
    {
        $query = $this->model->newQuery()->with(['user', 'showtime.movie', 'showtime.hall', 'tickets.seat.type']);

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['search']) && !empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                foreach ($this->model->searchable as $field) {
                    $q->orWhere($field, 'like', '%' . $filters['search'] . '%');
                }
            });
        }

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('created_at', [
                $filters['start_date'] . ' 00:00:00',
                $filters['end_date'] . ' 23:59:59'
            ]);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($filters['limit'] ?? 10);
    }

    public function findByCode(string $code)
    {
        return $this->model->where('booking_code', $code)
            ->with(['tickets.seat.type', 'user', 'showtime.movie', 'showtime.hall.cinema'])
            ->first();
    }
}
