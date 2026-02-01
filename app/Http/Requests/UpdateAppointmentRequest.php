<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'duration_minutes' => 'required|integer|min:15|max:240',
            'session_type' => 'required|in:Personal Training,Group Class,Consultation,Assessment',
            'status' => 'required|in:Scheduled,Completed,Cancelled,No-Show',
            'notes' => 'nullable|string|max:2000',
        ];
    }
}
