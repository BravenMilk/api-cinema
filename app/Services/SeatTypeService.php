<?php

namespace App\Services;

use App\Repositories\SeatTypeRepository;

class SeatTypeService extends BaseService
{
    protected $seatTypeRepo;

    public function __construct(SeatTypeRepository $seatTypeRepo)
    {
        $this->seatTypeRepo = $seatTypeRepo;
    }

    public function getAllTypes(array $filters = [])
    {
        $types = $this->seatTypeRepo->getAll($filters, $filters['limit'] ?? 10);
        return $this->response(true, 'Daftar tipe kursi berhasil dimuat', $types);
    }

    public function createType(array $data)
    {
        $this->authorizeRole('admin');
        $type = $this->seatTypeRepo->create($data);
        return $this->response(true, 'Tipe kursi berhasil ditambahkan', $type, 201);
    }

    public function updateType($id, array $data)
    {
        $this->authorizeRole('admin');
        if ($this->seatTypeRepo->update($id, $data)) {
            return $this->response(true, 'Tipe kursi berhasil diupdate', $this->seatTypeRepo->find($id));
        }
        return $this->response(false, 'Tipe kursi tidak ditemukan', null, 404);
    }

    public function deleteType($id)
    {
        $this->authorizeRole('admin');
        if ($this->seatTypeRepo->delete($id)) {
            return $this->response(true, 'Tipe kursi berhasil dihapus');
        }
        return $this->response(false, 'Tipe kursi tidak ditemukan', null, 404);
    }
}
