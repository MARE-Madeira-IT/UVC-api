<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        try {
            return Auth::user()->id;
        } catch (\Throwable $th) {
            return false;
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
            'site_id' => ['required', 'exists:sites,id', Rule::unique("reports", "site_id")
                ->where(function ($query) {
                    $query->where('survey_program_id', $this->survey_program_id)
                        ->where("time", $this->time)
                        ->where("depth_id", $this->depth_id)
                        ->where("replica", $this->replica);
                })->ignore($this?->report?->id)],
            'depth_id' => 'integer|exists:depths,id',
            'survey_program_id' => 'integer|exists:survey_programs,id',
            // 'code' => ['required', 'string', Rule::unique('reports', 'code')->where(function ($query) {
            //     $query->where('survey_program_id', $this->survey_program_id);
            // })->ignore($this?->report?->id)],
            'date' => 'required|date',
            'transect' => 'required|integer',
            'replica' => 'required|integer',
            'time' => 'required|numeric',
            'daily_dive' => 'required|integer',
            'heading' => 'nullable|integer',
            'heading_direction' => 'nullable|string',
            'dom_substrate' => 'nullable|string',
            'site_area' => 'nullable|string',
            'distance' => 'nullable|string',
            'functions' => 'required|array',
            'functions.*.value' => 'required|string',
            'functions.*.function_id' => 'required|integer|exists:survey_program_functions,id',
            'surveyed_area' => 'required|integer',
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
            'site_id.unique' => "The 'code' must be unique",
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
