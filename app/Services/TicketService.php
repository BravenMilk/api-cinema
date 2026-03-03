<?php

namespace App\Services;

use App\Repositories\TicketRepository;

class TicketService extends BaseService
{
    protected $ticketRepo;

    public function __construct(TicketRepository $ticketRepo)
    {
        $this->ticketRepo = $ticketRepo;
    }

    public function scanTicket(string $serial)
    {
        $this->authorizeRole(['staff', 'staf', 'admin', 'manager']);
        $ticket = $this->ticketRepo->findBySerial($serial);

        if (!$ticket) {
            return $this->response(false, 'Tiket tidak ditemukan!', null, 404);
        }

        if ($ticket->is_scanned) {
            return $this->response(false, 'Tiket sudah pernah digunakan pada ' . $ticket->scanned_at, null, 400);
        }

        // Cek apakah status booking sudah 'paid'
        if ($ticket->booking->status !== 'paid') {
            return $this->response(false, 'Pembayaran tiket ini belum lunas!', null, 403);
        }

        $this->ticketRepo->markAsScanned($ticket->id);

        return $this->response(true, 'Tiket VALID! Selamat menonton.', $ticket);
    }
}
