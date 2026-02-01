<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|max:100|unique:clients,email',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
            'address' => 'nullable|string|max:500',
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'membership_type' => 'required|in:Basic,Premium,VIP',
            'membership_start_date' => 'nullable|date',
            'membership_end_date' => 'nullable|date|after_or_equal:membership_start_date',
            'notes' => 'nullable|string|max:2000',
            'status' => 'required|in:Active,Inactive,On Hold',
        ];
    }
}
