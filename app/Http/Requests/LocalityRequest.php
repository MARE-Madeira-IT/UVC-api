<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use JWTAuth;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;

class LocalityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = Auth::user();

        return $user && true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'code' => 'required|string',
            'project_id' => 'required|integer|exists:projects,id',
            'sites' => 'array',
            'sites.*.id' => 'required|integer',
            'sites.*.name' => 'required|string',
            'sites.*.code' => 'required|string',
            'sites.*.latitude' => ['required', 'regex:/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,20})?))$/'],
            'sites.*.longitude' => ['required', 'regex:/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,20})?))$/'],
            'removeIDs' => 'array'
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
