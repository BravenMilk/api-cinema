<?php

namespace App\Services;

use App\Repositories\SeatRepository;

class SeatService extends BaseService
{
    protected $seatRepo;

    public function __construct(SeatRepository $seatRepo)
    {
        $this->seatRepo = $seatRepo;
    }

    public function getAllSeats(array $filters = [])
    {
        $seats = $this->seatRepo->with(['hall.cinema', 'type'])->getAll($filters, $filters['limit'] ?? 10);
        return $this->response(true, 'Daftar kursi berhasil dimuat', $seats);
    }

    public function getSeatLayout($hallId, $showtimeId)
    {
        $seats = $this->seatRepo->getAvailableSeats($hallId, $showtimeId);
        return $this->response(true, 'Layout kursi berhasil dimuat', $seats);
    }

    public function createSeat(array $data)
    {
        $this->authorizeRole(['admin', 'manager']);
        $seat = $this->seatRepo->create($data);
        return $this->response(true, 'Kursi berhasil ditambahkan', $seat, 201);
    }

    public function updateSeat($id, array $data)
    {
        $this->authorizeRole(['admin', 'manager']);
        if ($this->seatRepo->update($id, $data)) {
            return $this->response(true, 'Kursi berhasil diupdate', $this->seatRepo->find($id));
        }
        return $this->response(false, 'Kursi tidak ditemukan', null, 404);
    }

    public function deleteSeat($id)
    {
        $this->authorizeRole(['admin', 'manager']);
        if ($this->seatRepo->delete($id)) {
            return $this->response(true, 'Kursi berhasil dihapus');
        }
        return $this->response(false, 'Kursi tidak ditemukan', null, 404);
    }
}
