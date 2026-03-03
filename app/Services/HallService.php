<?php

namespace App\Services;

use App\Repositories\HallRepository;

class HallService extends BaseService
{
    protected $hallRepo;

    public function __construct(HallRepository $hallRepo)
    {
        $this->hallRepo = $hallRepo;
    }

    public function getAllHalls(array $filters = [])
    {
        $halls = $this->hallRepo->with(['cinema'])->getAll($filters, $filters['limit'] ?? 10);
        return $this->response(true, 'Daftar studio berhasil dimuat', $halls);
    }

    public function getHallDetails($id)
    {
        $hall = $this->hallRepo->getWithSeats($id);
        if (!$hall) {
            return $this->response(false, 'Studio tidak ditemukan', null, 404);
        }
        return $this->response(true, 'Detail studio berhasil dimuat', $hall);
    }

    public function createHall(array $data)
    {
        $this->authorizeRole(['admin', 'manager']);
        $hall = $this->hallRepo->create($data);
        return $this->response(true, 'Studio berhasil ditambahkan', $hall, 201);
    }

    public function updateHall($id, array $data)
    {
        $this->authorizeRole(['admin', 'manager']);
        if ($this->hallRepo->update($id, $data)) {
            return $this->response(true, 'Studio berhasil diupdate', $this->hallRepo->find($id));
        }
        return $this->response(false, 'Studio tidak ditemukan', null, 404);
    }

    public function deleteHall($id)
    {
        $this->authorizeRole(['admin', 'manager']);
        if ($this->hallRepo->delete($id)) {
            return $this->response(true, 'Studio berhasil dihapus');
        }
        return $this->response(false, 'Studio tidak ditemukan', null, 404);
    }
}
