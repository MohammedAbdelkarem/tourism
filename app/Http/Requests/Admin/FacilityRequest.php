<?php

namespace App\Http\Requests\Admin;


use App\Traits\ResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
// use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class FacilityRequest extends FormRequest
{
    use ResponseTrait; 
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
            'name'                       => 'required|string|between:2,100',
            'lat'                        => 'required|numeric',
            'long'                       => 'required|numeric',
            'bio'                        => 'required|string',
            'photo'                      => 'required|mimes:jpg,jpeg,png|max:2048',
            'number_of_available_places' => 'required|integer|min:1',
            'price_per_person'           => 'required|integer',
            'type'                       => 'required|string|between:2,100',
            'country_id'                 => 'required|integer',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw (new HttpResponseException(
            $this->SendResponse(response::HTTP_UNPROCESSABLE_ENTITY , 'validation error' , $validator->errors()->toArray()))
        );
    }
}
