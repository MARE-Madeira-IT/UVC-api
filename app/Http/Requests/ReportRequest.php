<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;

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
            'site_id' => 'required|exists:sites,id',
            'depth_id' => 'integer|exists:depths,id',
            'project_id' => 'integer|exists:projects,id',
            'code' => 'required|string',
            'date' => 'required|date',
            'transect' => 'required|integer',
            'replica' => 'required|integer',
            'time' => 'required|integer',
            'daily_dive' => 'required|integer',
            'latitude' => ['required', 'regex:/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,20})?))$/'],
            'longitude' => ['required', 'regex:/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,20})?))$/'],
            'heading' => 'nullable|integer',
            'heading_direction' => 'nullable|string',
            'dom_substrate' => 'nullable|string',
            'site_area' => 'nullable|string',
            'distance' => 'nullable|string',
            'functions' => 'required|array',
            'functions.*.value' => 'required|string',
            'functions.*.function_id' => 'required|integer|exists:functions,id',
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
