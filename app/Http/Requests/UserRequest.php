<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:1|max:50|regex:/^[a-zA-Z0-9\s]+$/',
            'email' => 'required|regex:/(.+)@(.+)\.(.+)/i|unique:users,email',
            'password' => 'required|string|min:6|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'confirm_password' => 'required',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => $errors,
            'data' => "",
        ], 422));
    }
    public function messages() //OPTIONAL
    {
        return [
            'name.required' => "The name field is required.",
            'name.min' => "The name field must contain atleast 1 alphabet.",
            'name.max' => "The name field must not contain more than 50 alphabets.",
            'name.regex' => "The name field should only contain alphabets.",
            'email.required' => "The email field is required.",
            'email.regex' => "The email format is invalid.",
            'email.unique' => "The email already exist.",
            'password.required' => "The password field is required.",
            'password.min' => "The password length must be greater than 6 characters.",
            'password.regex' => "The password must contain 1 Upper Case,1 Lower Case, 1 Numeric and 1 Special Character.",
            'confirm_password.required' => "The confirm password field is required.",
            'confirm_password.min' => "The confirm password length must be greater than 6 characters.",
            'confirm_password.regex' => "The confirm password must contain 1 Upper Case,1 Lower Case, 1 Numeric and 1 Special Character.",

        ];
    }

}
