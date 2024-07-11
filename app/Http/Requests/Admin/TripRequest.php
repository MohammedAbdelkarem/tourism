<?php

namespace App\Http\Requests\Admin;


use App\Traits\ResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
// use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Validator;

use Illuminate\Http\Exceptions\HttpResponseException;
class TripRequest extends FormRequest
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
            'name'          => 'required|string|between:2,100',
            'lat'           => 'required|numeric',
            'long'          => 'required|numeric',
            'bio'           => 'required|string',
            'end_date'      => 'required|date',
            'start_date'    => 'required|date',
            'photo'         => 'required|mimes:jpg,jpeg,png|max:2048',
            'guide_id'      => 'required|integer',
            'country_id'    => 'required|integer',
            'offer_ratio'   => 'nullable|integer|between:0,100',
            // 'days'          => ['required', 'array'],
            // 'days.*.id'     => ['required', 'numeric'],
            // 'days.*.date'   => ['required', 'date_format:Y-m-d'],
            // 'days.*.facilities' => ['required', ]
            // 'days.*.faclilifwi.*.kdjfkf'
        ];

    }


     /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'end_date.date_format' => 'The :attribute must be in the format YYYY-MM-DD.',
            'tart_date.date_format' => 'The :attribute must be in the format YYYY-MM-DD.',
        ];
    }

    //...



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
