<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class LatLongRequest extends FormRequest
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
            'lat'           => 'required|numeric',
            'long'          => 'required|numeric',
        ];
    }
    
    /**
     * Normalize the longitude value if it's outside the range of -180 to 180
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    public function normalizeLongitude($key, $value)
    {
        
    $value = (float) $value; // cast to float
    if ($value < -180) {
        $value += 360;
    } elseif ($value > 180) {
        $value -= 360;
    }
    return $value;
    }

    /**
     * Prepare the data for validation
     *
     * @return void
     */
    public function prepareForValidation()
{
    $this->merge([
        'lat' => $this->normalizeLatitude('lat', $this->input('lat')),
        'long' => $this->normalizeLongitude('long', $this->input('long')),
    ]);
}

public function normalizeLatitude($key, $value)
{

    $value = (float) $value; 
    if ($value < -90) {
        $value = -90;
    } elseif ($value > 90) {
        $value = 90;
    }
    return $value;
}
}
