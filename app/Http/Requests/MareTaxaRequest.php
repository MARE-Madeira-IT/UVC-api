<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use JWTAuth;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MareTaxaRequest extends FormRequest
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
        $id = $this->id;
        $name = $this->name;
        $project_id = $this->project_id;


        return [
            'name' => [
                'required',
                'string',
                Rule::unique('mare_taxas')->where(function ($query) use (
                    $id,
                    $name,
                    $project_id
                ) {
                    $query->where('name', $name)->where('project_id', $project_id);
                    if ($id) {
                        $query->where('id', '!=', $id);
                    }
                    return $query;
                })
            ],
            'genus' => 'required|string',
            'species' => 'nullable|string',
            'phylum' => 'nullable|string',
            'category_id' => 'required|integer',
            'project_id' => 'required|integer',
            'validated' => 'nullable|boolean',
            'indicators' => 'nullable|array',
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
