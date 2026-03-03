<?php

namespace App\Repositories;

use App\Models\Seat;

class SeatRepository extends BaseRepository
{
    public function __construct(Seat $model)
    {
        parent::__construct($model);
    }

    public function getAvailableSeats($hallId, $showtimeId)
    {
        // Ambil semua kursi aktif di hall, beserta relasi type
        $seats = $this->model
            ->where('hall_id', $hallId)
            ->where('is_active', true)
            ->with(['type'])
            ->get();

        // Tandai kursi yang sudah terisi (ada tiket dengan booking paid/pending di showtime ini)
        return $seats->map(function ($seat) use ($showtimeId) {
            $isTaken = $seat->tickets()
                ->whereHas('booking', function ($q) use ($showtimeId) {
                    $q->where('showtime_id', $showtimeId)
                      ->whereIn('status', ['paid', 'pending']);
                })
                ->exists();

            $seat->is_taken = $isTaken;
            // Alias agar frontend bisa pakai seat.row dan seat.number
            $seat->row    = $seat->row_label;
            $seat->number = $seat->seat_number;

            return $seat;
        });
    }
}

