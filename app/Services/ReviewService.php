<?php

namespace App\Services;

use App\Repositories\ReviewRepository;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class ReviewService extends BaseService
{
    protected $reviewRepo;

    public function __construct(ReviewRepository $reviewRepo)
    {
        $this->reviewRepo = $reviewRepo;
    }

    /**
     * Ambil semua ulasan untuk sebuah film (publik).
     * Jika ada Bearer token valid, sertakan can_review dan my_review.
     */
    public function getMovieReviews(string $movieId, array $filters = [])
    {
        $reviews   = $this->reviewRepo->getByMovie($movieId, $filters);
        $avgRating = $this->reviewRepo->getAverageRating($movieId);

        $canReview = false;
        $myReview  = null;

        $user = auth('sanctum')->user();

        if ($user) {
            $canReview = Booking::where('user_id', $user->id)
                ->where('status', 'paid')
                ->whereHas('showtime', fn($q) => $q->where('movie_id', $movieId))
                ->exists();

            $myReview = $this->reviewRepo->findByUserAndMovie($user->id, $movieId);
        }

        return $this->response(true, 'Ulasan berhasil dimuat', [
            'average_rating' => $avgRating,
            'reviews'        => $reviews,
            'can_review'     => $canReview,
            'my_review'      => $myReview,
        ]);
    }

    /**
     * Buat ulasan baru. User hanya bisa mengulas jika sudah punya booking 'paid'
     * untuk film tersebut, dan belum pernah mengulas film yang sama.
     */
    public function createReview(string $movieId, array $data)
    {
        $user = Auth::user();

        // 1. Cek apakah user sudah pernah mengulas film ini
        $existing = $this->reviewRepo->findByUserAndMovie($user->id, $movieId);
        if ($existing) {
            return $this->response(false, 'Anda sudah pernah memberikan ulasan untuk film ini.', null, 422);
        }

        // 2. Cek apakah user punya booking 'paid' untuk film ini
        $hasPaidBooking = Booking::where('user_id', $user->id)
            ->where('status', 'paid')
            ->whereHas('showtime', fn($q) => $q->where('movie_id', $movieId))
            ->exists();

        if (!$hasPaidBooking) {
            return $this->response(false, 'Anda hanya bisa mengulas film yang sudah Anda tonton (tiket terbayar).', null, 403);
        }

        // 3. Validasi rating 1-5
        if ($data['rating'] < 1 || $data['rating'] > 5) {
            return $this->response(false, 'Rating harus antara 1 sampai 5.', null, 422);
        }

        $review = $this->reviewRepo->create([
            'user_id'  => $user->id,
            'movie_id' => $movieId,
            'rating'   => $data['rating'],
            'comment'  => $data['comment'] ?? null,
        ]);

        $review->load('user:id,name');

        return $this->response(true, 'Ulasan berhasil ditambahkan.', $review, 201);
    }

    /**
     * Update ulasan milik user sendiri.
     */
    public function updateReview(string $movieId, array $data)
    {
        $user = Auth::user();

        $review = $this->reviewRepo->findByUserAndMovie($user->id, $movieId);
        if (!$review) {
            return $this->response(false, 'Ulasan tidak ditemukan.', null, 404);
        }

        if (isset($data['rating']) && ($data['rating'] < 1 || $data['rating'] > 5)) {
            return $this->response(false, 'Rating harus antara 1 sampai 5.', null, 422);
        }

        $review->update(array_filter([
            'rating'  => $data['rating'] ?? $review->rating,
            'comment' => $data['comment'] ?? $review->comment,
        ]));

        $review->load('user:id,name');

        return $this->response(true, 'Ulasan berhasil diperbarui.', $review);
    }

    /**
     * Hapus ulasan milik user sendiri (atau admin).
     */
    public function deleteReview(string $movieId)
    {
        $user = Auth::user();
        $userRole = strtolower($user->role->name ?? '');

        $review = $this->reviewRepo->findByUserAndMovie($user->id, $movieId);

        // Admin bisa hapus ulasan siapapun via route berbeda, ini untuk owner
        if (!$review) {
            return $this->response(false, 'Ulasan tidak ditemukan.', null, 404);
        }

        $review->delete();

        return $this->response(true, 'Ulasan berhasil dihapus.');
    }

    /**
     * Hapus ulasan manapun (khusus admin).
     */
    public function adminDeleteReview(string $reviewId)
    {
        $this->authorizeRole(['admin', 'manager']);

        $review = $this->reviewRepo->find($reviewId);
        if (!$review) {
            return $this->response(false, 'Ulasan tidak ditemukan.', null, 404);
        }

        $review->delete();

        return $this->response(true, 'Ulasan berhasil dihapus oleh admin.');
    }
}
