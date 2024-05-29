<?php

namespace App\Http\Requests\Auth\User;

use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
// use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class InformationRequest extends FormRequest
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
            'name' => 'required',
            'phone' => ['required'  , 'unique:users,phone' , 'phone:AUTO'],
            'password' => [
                'required' ,
                'confirmed' ,
                Password::min(8)
                ->letters()
                ->numbers()
            ],
        ];
    }

    public function messages()
    {
        return[
            'phone' => 'Phone number is not valid'
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
