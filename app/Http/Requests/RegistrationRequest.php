<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
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
    // public function rules()
    // {
    //     return [
    //         //
    //         'name' =>['required','string','min:4'],
    //         'email' => ['required','email:filter','unique:users'],
    //         'password' => ['required','string','min:6', 'confirmed']
    //     ];
    // }

    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // Validate email is unique in the 'user' table
            'password' => [
                'required',
                'string',
                'min:6', // Minimum of 6 characters
                'regex:/[a-z]/', // Must contain at least one lowercase letter
                'regex:/[A-Z]/', // Must contain at least one uppercase letter
                'regex:/[0-9]/', // Must contain at least one digit
                'regex:/[@$!%*?&#]/', // Must contain a special character
            ],
            'business_name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'currency' => 'required|string|max:3',
           
        ];
    }

    public function messages()
    {
        return [
            'Password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one digit, and one special character.',
            'Email.unique' => 'The email has already been taken.',
        ];
    }
}
