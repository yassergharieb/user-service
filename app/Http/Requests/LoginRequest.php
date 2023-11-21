<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Http\traits\ResponseHandeler;

class LoginRequest extends FormRequest
{
    use ResponseHandeler;
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
            "email" => "required|email|exists:users,email",
            "password" => "required"
        ];
    }


    protected  function failedValidation(Validator $validator)
    {
        return $this->validationfails($validator);
    }
}
