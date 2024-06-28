<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DayRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
          
            'date' => 'required|date',
            'day_id' => 'required|integer',
            'trip_id' => 'required|integer',
            // 'start_time' => 'required|date_format:H:i:s',
            // 'end_time' => 'required|date_format:H:i:s',
            // 'selected_facilities' => 'required|array|min:1',
            // 'selected_facilities.*' => 'required|integer|exists:facilities,id',
   
        ];
    }
}
