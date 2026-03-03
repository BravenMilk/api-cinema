<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'showtime_id' => 'required|exists:showtimes,id',
            'total_price' => 'required|numeric|min:0',
            'seat_ids'    => 'required|array|min:1',
            'seat_ids.*'  => 'exists:seats,id',
        ];
    }
}
