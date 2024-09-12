<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RegisterRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        if ($this->userable_type == 'UserDiveCenter') {
            $this->merge([
                'userable_type' => 'App\UserDiveCenter',
                'role' => 'dive-center'
            ]);
        } else if ($this->userable_type == 'UserPerson') {
            $this->merge([
                'userable_type' => 'App\Models\UserPerson',
                'role' => 'person'
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:2',
            'userable_type' => 'required|string',
            'role' => 'required|string|in:person,dive-center',

            'name' => 'required|string|min:2',
            'latitude' => ['required_if:userable_type,UserDiveCenter', 'regex:/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,20})?))$/'],
            'longitude' => ['required_if:userable_type,UserDiveCenter', 'regex:/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,20})?))$/'],
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'latitude.regex' => 'The latitude format is invalid. Latitude range from -90 to 90',
            'longitude.regex' => 'The longitude format is invalid. Longitude range from -180 to 80',
        ];
    }

    /**
     * Return validation errors as json response
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422));
    }
}
