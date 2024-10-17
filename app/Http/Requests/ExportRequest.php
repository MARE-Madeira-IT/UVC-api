<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;


class ExportRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'survey_program_id' => 'required|integer|exists:survey_programs,id',
            'reports' => 'nullable|array',
            'reports.*' => 'exists:reports,id',
            'taxas' => 'nullable|array',
            'depths' => 'nullable|array',
            'depths.*' => 'exists:depths,id',
            'dates' => 'nullable|array',
            'dates.0' => 'date',
            'dates.1' => 'date',
            'sites' => 'nullable|array',
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
