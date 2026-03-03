<?php

namespace App\Services;

use App\Repositories\ShowtimeRepository;

class ShowtimeService extends BaseService
{
    protected $showtimeRepo;

    public function __construct(ShowtimeRepository $showtimeRepo)
    {
        $this->showtimeRepo = $showtimeRepo;
    }

    public function getAllShowtimes(array $filters = [])
    {
        $showtimes = $this->showtimeRepo->getAllFiltered($filters, $filters['limit'] ?? 20);
        return $this->response(true, 'Daftar jadwal tayang berhasil dimuat', $showtimes);
    }

    public function getShowtimeDetails($id)
    {
        $showtime = $this->showtimeRepo->getDetails($id);
        if (!$showtime) {
            return $this->response(false, 'Jadwal tayang tidak ditemukan', null, 404);
        }
        return $this->response(true, 'Jadwal berhasil dimuat', $showtime);
    }

    public function createShowtime(array $data)
    {
        $this->authorizeRole(['admin', 'manager']);
        $showtime = $this->showtimeRepo->create($data);
        return $this->response(true, 'Jadwal tayang berhasil ditambahkan', $showtime, 201);
    }

    public function updateShowtime($id, array $data)
    {
        $this->authorizeRole(['admin', 'manager']);
        if ($this->showtimeRepo->update($id, $data)) {
            return $this->response(true, 'Jadwal tayang berhasil diupdate', $this->showtimeRepo->find($id));
        }
        return $this->response(false, 'Jadwal tayang tidak ditemukan', null, 404);
    }

    public function deleteShowtime($id)
    {
        $this->authorizeRole('admin');
        if ($this->showtimeRepo->delete($id)) {
            return $this->response(true, 'Jadwal tayang berhasil dihapus');
        }
        return $this->response(false, 'Jadwal tayang tidak ditemukan', null, 404);
    }
}
