<?php

namespace App\Services;

use App\Repositories\BookingRepository;
use App\Repositories\TicketRepository;
use App\Repositories\ShowtimeRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class BookingService extends BaseService
{
    protected $bookingRepo, $ticketRepo, $showtimeRepo;

    public function __construct(
        BookingRepository $bookingRepo,
        TicketRepository $ticketRepo,
        ShowtimeRepository $showtimeRepo
    ) {
        $this->bookingRepo = $bookingRepo;
        $this->ticketRepo = $ticketRepo;
        $this->showtimeRepo = $showtimeRepo;
    }

    public function getMyBookings(array $filters = [])
    {
        $this->checkExpirations();
        $filters['user_id'] = Auth::id();
        $bookings = $this->bookingRepo->getFiltered($filters);
        return $this->response(true, 'Riwayat pemesanan berhasil dimuat', $bookings);
    }

    public function getAllBookings(array $filters = [])
    {
        $this->authorizeRole(['admin', 'manager', 'staff', 'staf']);
        $bookings = $this->bookingRepo->getFiltered($filters);
        return $this->response(true, 'Semua data pemesanan berhasil dimuat', $bookings);
    }

    public function getBookingRecap(array $filters)
    {
        $this->authorizeRole(['admin', 'manager']);
        
        $bookings = $this->bookingRepo->getFiltered(array_merge($filters, ['limit' => 1000])); // Ambil banyak untuk recap
        
        $totalSales = $bookings->sum('total_price');
        $totalTickets = 0;
        foreach($bookings as $booking) {
             // Asumsi tickets count diload atau hitung manual
             // Karena di repo belum diload tickets, kita load count saja atau agregat
             // Sederhananya, hitung transaction count dulu
        }
        
        $recap = [
            'total_transactions' => $bookings->total(),
            'total_sales' => $totalSales,
            'period' => ($filters['start_date'] ?? 'All') . ' - ' . ($filters['end_date'] ?? 'All'),
            'data' => $bookings->items()
        ];

        return $this->response(true, 'Rekapitulasi penjualan', $recap);
    }

    public function createBooking(array $data)
    {
        // Semua role yang login boleh pesan
        $this->authorizeRole(['customer', 'staff', 'staf', 'manager', 'admin']);
        return DB::transaction(function () use ($data) {
            // 1. Ambil data jadwal dengan Lock untuk mencegah Race Condition
            $showtime = $this->showtimeRepo->find($data['showtime_id']);

            if (!$showtime) {
                return $this->response(false, 'Jadwal tayang tidak ditemukan', null, 404);
            }

            // --- VALIDASI WINDOW BOOKING ---
            $now = now();
            
            // 1. Jika ada window yang diset, cek apakah sekarang di dalam window
            if ($showtime->booking_start_at || $showtime->booking_end_at) {
                if ($showtime->booking_start_at && $now->lessThan($showtime->booking_start_at)) {
                    $openDate = $showtime->booking_start_at->format('d/m/Y H:i');
                    return $this->response(false, "Maaf, pemesanan belum dibuka. Pembelian dibuka pada: {$openDate}", null, 400);
                }
                
                if ($showtime->booking_end_at && $now->greaterThan($showtime->booking_end_at)) {
                    return $this->response(false, 'Maaf, periode pemesanan untuk jadwal ini sudah berakhir.', null, 400);
                }
            } else {
                // 2. Jika tidak ada window (Legacy), gunakan patokan waktu tayang
                if ($now->greaterThanOrEqualTo($showtime->start_time)) {
                    return $this->response(false, 'Maaf, jadwal tayang sudah dimulai atau sudah lewat.', null, 400);
                }
            }

            // --- VALIDASI KAPASITAS ROOM (MAX 60) ---
            // Hitung total kursi yang sudah terjual untuk jadwal ini
            // Kita hitung dari jumlah ticket yang terjual di showtime ini
            // Asumsi: Kita bisa hitung via Ticket -> Booking -> Showtime OR Booking -> Tickets
            // Query: Hitung jumlah Ticket di mana Booking-nya punya showtime_id ini dan status != failed/expired.
            $bookedSeatsCount = \App\Models\Ticket::whereHas('booking', function($q) use ($showtime) {
                $q->where('showtime_id', $showtime->id)
                  ->whereIn('status', ['paid', 'pending']); // Pending juga dihitung agar tidak overbook
            })->count();

            // Tambahkan jumlah kursi yang akan dipesan sekarang
            $seatsToBookCount = count($data['seat_ids']);
            
            if (($bookedSeatsCount + $seatsToBookCount) > 60) {
                 return $this->response(false, 'Maaf, Studio Penuh! Kapasitas maksimal 60 orang.', null, 400);
            }

            // --- VALIDASI DOUBLE BOOKING ---
            // Cari tiket yang sudah dipesan untuk kursi ini
            $conflictingTickets = \App\Models\Ticket::whereIn('seat_id', $data['seat_ids'])
                ->whereHas('booking', function($q) use ($showtime) {
                    $q->where('showtime_id', $showtime->id)
                      ->whereIn('status', ['paid', 'pending']);
                })
                ->with('booking')
                ->get();

            if ($conflictingTickets->isNotEmpty()) {
                // Cek apakah SEMUA kursi yang bermasalah ini sebenarnya milik user yang sama dan statusnya masih PENDING
                $allSameUserPending = $conflictingTickets->every(function($ticket) {
                    return $ticket->booking->user_id === Auth::id() && $ticket->booking->status === 'pending';
                });

                // Jika ya (misal user refresh halaman), kembalikan booking yang sudah ada tersebut
                if ($allSameUserPending && $conflictingTickets->groupBy('booking_id')->count() === 1) {
                    $existingBooking = $conflictingTickets->first()->booking;
                    return $this->response(true, 'Booking Anda yang tertunda ditemukan', $existingBooking, 200);
                }

                return $this->response(false, 'Maaf, salah satu kursi yang Anda pilih barusan sudah dipesan orang lain.', null, 400);
            }

            // 2. Logika Generate Kode Booking Unik
            $bookingCode = 'TIC-' . strtoupper(Str::random(8));

            // 3. Simpan Header Booking
            $booking = $this->bookingRepo->create([
                'booking_code' => $bookingCode,
                'user_id' => Auth::id(),
                'showtime_id' => $data['showtime_id'],
                'total_price' => $data['total_price'],
                'status' => 'pending',
                'payment_limit' => now()->addMinutes(15)
            ]);

            // 4. Simpan Detail Tiket per Kursi yang dipilih
            foreach ($data['seat_ids'] as $seatId) {
                $this->ticketRepo->create([
                    'booking_id' => $booking->id,
                    'seat_id' => $seatId,
                    'ticket_serial' => 'SN-' . strtoupper(Str::random(12)),
                    'is_scanned' => false
                ]);
            }

            return $this->response(true, 'Booking berhasil dibuat, silakan bayar', $booking, 201);
        });
    }

    public function updateStatus(string $bookingCode, string $status)
    {
        $booking = $this->bookingRepo->findByCode($bookingCode);

        if (!$booking) {
            return $this->response(false, 'Booking tidak ditemukan', null, 404);
        }

        if ($booking->status === $status) {
             return $this->response(true, 'Status sudah sesuai', $booking);
        }

        $booking->update(['status' => $status]);

        return $this->response(true, 'Status booking berhasil diperbarui', $booking);
    }

    public function cancelBooking(string $bookingCode)
    {
        $booking = $this->bookingRepo->findByCode($bookingCode);

        if (!$booking) {
            return $this->response(false, 'Booking tidak ditemukan', null, 404);
        }

        if ($booking->user_id !== Auth::id()) {
            return $this->response(false, 'Unauthorized', null, 403);
        }

        if ($booking->status !== 'pending') {
            return $this->response(false, 'Hanya booking pending yang bisa dibatalkan', null, 400);
        }

        $booking->update(['status' => 'cancelled']);

        return $this->response(true, 'Booking berhasil dibatalkan', $booking);
    }

    private function checkExpirations()
    {
        \App\Models\Booking::where('status', 'pending')
            ->where('payment_limit', '<', now())
            ->update(['status' => 'expired']);
    }
}
