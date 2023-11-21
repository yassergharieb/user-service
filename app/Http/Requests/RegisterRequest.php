<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\traits\ResponseHandeler;
use Illuminate\Contracts\Validation\Validator;
class RegisterRequest extends FormRequest
{
    use ResponseHandeler;
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
            "name" => "required|min:5|max:10",
            "email" => "required|email|unique:users,email", 
            "password" => "required"
        ];
    }


    protected  function failedValidation(Validator $validator)
    {
        return $this->validationfails($validator);
    }
}
