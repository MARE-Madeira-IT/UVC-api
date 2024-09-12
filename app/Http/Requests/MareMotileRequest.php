<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use JWTAuth;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;

class MareMotileRequest extends FormRequest
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
            'report_id' => 'required|integer|exists:mare_reports,id',
            'type' => 'required|string',
            'motiles.*.taxa_id' => 'required|integer|exists:mare_taxas,id',
            'motiles.*.size_category_id' => 'nullable|integer|exists:mare_size_categories,id',
            'motiles.*.size' => 'nullable|min:0|max:10000',
            'motiles.*.ntotal' => 'nullable',
            'motiles.*.notes' => 'nullable',
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
