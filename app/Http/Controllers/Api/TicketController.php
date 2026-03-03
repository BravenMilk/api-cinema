<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TicketService;
use App\Http\Requests\Api\ScanTicketRequest; // Import Request
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function scan(ScanTicketRequest $request): JsonResponse
    {
        // $request->serial otomatis tervalidasi ada di tabel tickets
        $result = $this->ticketService->scanTicket($request->serial);

        return response()->json($result, $result['code']);
    }
}
