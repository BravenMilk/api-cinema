<?php

namespace App\Services;

use App\Repositories\MovieRepository;

class MovieService extends BaseService
{
    // Tambahkan tipe data ini agar VS Code tidak bingung (Kuning hilang)
    protected MovieRepository $movieRepo;

    public function __construct(MovieRepository $movieRepo)
    {
        $this->movieRepo = $movieRepo;
    }

    public function getHomePageData(): array
    {
        // Tetap gunakan method khusus untuk home page (Now Showing)
        $movies = $this->movieRepo->getNowShowingWithShowtimes();
        return $this->response(true, 'Data film berhasil diambil', $movies);
    }

    public function getAllMovies(array $filters)
    {
        // Gunakan getAll dari BaseRepository untuk search & pagination
        $movies = $this->movieRepo->getAll($filters, $filters['limit'] ?? 10);
        return $this->response(true, 'Data film berhasil dimuat', $movies);
    }

    // PASTIKAN FUNGSI INI ADA (Agar merah di Controller hilang)
    public function getMovieDetail($id): array
    {
        $movie = $this->movieRepo->find($id);
        if (!$movie) {
            return $this->response(false, 'Film tidak ditemukan', null, 404);
        }
        return $this->response(true, 'Detail film berhasil dimuat', $movie);
    }

    public function createMovie(array $data)
    {
        $this->authorizeRole(['admin', 'manager']);
        $movie = $this->movieRepo->create($data);
        return $this->response(true, 'Film berhasil ditambahkan', $movie, 201);
    }

    public function updateMovie($id, array $data)
    {
        $this->authorizeRole(['admin', 'manager']);
        if ($this->movieRepo->update($id, $data)) {
            return $this->response(true, 'Film berhasil diupdate', $this->movieRepo->find($id));
        }
        return $this->response(false, 'Film tidak ditemukan', null, 404);
    }

    public function deleteMovie($id)
    {
        $this->authorizeRole('admin');
        if ($this->movieRepo->delete($id)) {
            return $this->response(true, 'Film berhasil dihapus');
        }
        return $this->response(false, 'Film tidak ditemukan', null, 404);
    }
}
