<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ReviewService;
use App\Http\Requests\Api\StoreReviewRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    protected $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    /**
     * GET /movies/{movieId}/reviews
     * Publik - siapa saja bisa lihat ulasan
     */
    public function index(string $movieId, Request $request): JsonResponse
    {
        $result = $this->reviewService->getMovieReviews($movieId, $request->all());
        return response()->json($result, $result['code']);
    }

    /**
     * POST /movies/{movieId}/reviews
     * Customer - buat ulasan baru
     */
    public function store(string $movieId, StoreReviewRequest $request): JsonResponse
    {
        $result = $this->reviewService->createReview($movieId, $request->validated());
        return response()->json($result, $result['code']);
    }

    /**
     * PUT /movies/{movieId}/reviews
     * Customer - edit ulasan sendiri
     */
    public function update(string $movieId, StoreReviewRequest $request): JsonResponse
    {
        $result = $this->reviewService->updateReview($movieId, $request->validated());
        return response()->json($result, $result['code']);
    }

    /**
     * DELETE /movies/{movieId}/reviews
     * Customer - hapus ulasan sendiri
     */
    public function destroy(string $movieId): JsonResponse
    {
        $result = $this->reviewService->deleteReview($movieId);
        return response()->json($result, $result['code']);
    }

    /**
     * DELETE /reviews/{reviewId}
     * Admin - hapus ulasan siapapun
     */
    public function adminDestroy(string $reviewId): JsonResponse
    {
        $result = $this->reviewService->adminDeleteReview($reviewId);
        return response()->json($result, $result['code']);
    }
}
