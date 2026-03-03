<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class SeatLayoutRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            // hall_id opsional - bisa diambil otomatis dari showtime_id
            'hall_id'     => 'nullable|exists:halls,id',
            // showtime_id wajib
            'showtime_id' => 'required|exists:showtimes,id',
        ];
    }
}

