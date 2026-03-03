<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Mendapatkan ID user yang sedang login
        $userId = $this->user()->id;

        return [
            'name' => 'required|string|max:255',
            // Email harus unik kecuali milik user sendiri
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'password' => 'nullable|string|min:8|confirmed', // Opsional, hanya jika ingin ganti password
            'phone' => 'nullable|string|max:20',
        ];
    }
}
