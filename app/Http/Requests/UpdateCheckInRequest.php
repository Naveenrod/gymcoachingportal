<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCheckInRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'loom_link' => 'nullable|url|max:500',
            'package' => 'nullable|string|max:100',
            'check_in_frequency' => 'nullable|string|max:50',
            'check_in_day' => 'nullable|string|max:50',
            'submitted' => 'nullable|in:Submitted,',
            'rank' => 'nullable|string|max:50',
        ];
    }
}
