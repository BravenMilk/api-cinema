<?php

namespace App\Services;

use App\Repositories\CityRepository;

class LocationService extends BaseService
{
    protected $cityRepo;

    public function __construct(CityRepository $cityRepo)
    {
        $this->cityRepo = $cityRepo;
    }

    public function getAllCities(array $filters = [])
    {
        $cities = $this->cityRepo->getAll($filters, $filters['limit'] ?? 10);
        return $this->response(true, 'Daftar kota berhasil diambil', $cities);
    }

    public function createCity(array $data)
    {
        $this->authorizeRole(['admin', 'manager']);
        $city = $this->cityRepo->create($data);
        return $this->response(true, 'Kota berhasil ditambahkan', $city, 201);
    }

    public function updateCity($id, array $data)
    {
        $this->authorizeRole(['admin', 'manager']);
        if ($this->cityRepo->update($id, $data)) {
            return $this->response(true, 'Kota berhasil diupdate', $this->cityRepo->find($id));
        }
        return $this->response(false, 'Kota tidak ditemukan', null, 404);
    }

    public function deleteCity($id)
    {
        $this->authorizeRole('admin');
        if ($this->cityRepo->delete($id)) {
            return $this->response(true, 'Kota berhasil dihapus');
        }
        return $this->response(false, 'Kota tidak ditemukan', null, 404);
    }
}
