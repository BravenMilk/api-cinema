<?php

namespace App\Repositories;

use App\Models\Ticket;

class TicketRepository extends BaseRepository
{
    public function __construct(Ticket $model)
    {
        parent::__construct($model);
    }

    public function findBySerial(string $serial)
    {
        return $this->model->where('ticket_serial', $serial)
            ->with(['booking.user', 'seat.hall', 'booking.showtime.movie'])
            ->first();
    }

    public function markAsScanned(string $id)
    {
        return $this->update($id, [
            'is_scanned' => true,
            'scanned_at' => now()
        ]);
    }
}
