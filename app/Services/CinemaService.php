<?php

namespace App\Services;

use App\Repositories\CinemaRepository;

class CinemaService extends BaseService
{
    protected $cinemaRepo;

    public function __construct(CinemaRepository $cinemaRepo)
    {
        $this->cinemaRepo = $cinemaRepo;
    }

    public function getCinemasByCity($cityId)
    {
        $cinemas = $this->cinemaRepo->getByCity($cityId);
        return $this->response(true, 'Data bioskop berhasil dimuat', $cinemas);
    }

    public function getAllCinemas(array $filters)
    {
        $cinemas = $this->cinemaRepo->with(['city'])->getAll($filters, $filters['limit'] ?? 10);
        return $this->response(true, 'Data bioskop berhasil dimuat', $cinemas);
    }

    public function createCinema(array $data)
    {
        $this->authorizeRole(['admin', 'manager']);
        $cinema = $this->cinemaRepo->create($data);
        return $this->response(true, 'Bioskop berhasil ditambahkan', $cinema, 201);
    }

    public function updateCinema($id, array $data)
    {
        $this->authorizeRole(['admin', 'manager']);
        if ($this->cinemaRepo->update($id, $data)) {
            return $this->response(true, 'Bioskop berhasil diupdate', $this->cinemaRepo->find($id));
        }
        return $this->response(false, 'Bioskop tidak ditemukan', null, 404);
    }

    public function deleteCinema($id)
    {
        $this->authorizeRole('admin');
        if ($this->cinemaRepo->delete($id)) {
            return $this->response(true, 'Bioskop berhasil dihapus');
        }
        return $this->response(false, 'Bioskop tidak ditemukan', null, 404);
    }
}
