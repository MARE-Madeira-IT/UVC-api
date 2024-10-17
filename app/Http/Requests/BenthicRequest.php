<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;

class BenthicRequest extends FormRequest
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
            'report_id' => 'required|integer|exists:reports,id',
            'benthics' => 'required|array',
            'benthics.*.id' => 'nullable|integer|exists:benthics,id',
            'benthics.*.taxa_id' => 'nullable|array|size:2',
            'benthics.*.taxa_id.1' => 'nullable|integer|exists:taxas,id',
            'benthics.*.p' => 'required|integer|min:1|max:100',
            'benthics.*.substrate_id' => 'required|integer|exists:substrates,id',
            'benthics.*.notes' => 'nullable|string',
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
