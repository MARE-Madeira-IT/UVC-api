<?php

namespace App\Http\Controllers;

use App\Exports\SurveyProgramExport;
use App\Http\Requests\ExportRequest;
use App\Http\Resources\ExportResource;
use App\Jobs\SurveyProgramExportCompletedJob;
use App\Jobs\SurveyProgramExportJob;
use App\Models\Export;
use App\Models\SurveyProgram;
use App\QueryFilters\ExportFilters;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ExportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ExportFilters $filters)
    {
        return ExportResource::collection(Export::filterBy($filters)->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExportRequest $request)
    {
        $validator = $request->validated();
        $surveyProgram = SurveyProgram::findOrFail($request->survey_program_id);

        $newEntry = Export::create([
            'state' => 'pending',
            'survey_program_id' => $validator["survey_program_id"],
            'date_from' => array_key_exists("dates", $validator)
                && $validator["dates"][0] ? Carbon::parse($validator["dates"][0]) : null,
            'date_to' => array_key_exists("dates", $validator)
                && $validator["dates"][1] ? Carbon::parse($validator["dates"][1]) : null,
        ]);

        if (array_key_exists("reports", $validator)) {
            $newEntry->reports()->attach($validator["reports"]);
        }
        if (array_key_exists("depths", $validator)) {
            $newEntry->depths()->attach($validator["depths"]);
        }


        if (array_key_exists("sites", $validator)) {
            $localityIds = array_map(function ($el) {
                return (int) $el[0];
            }, array_filter($validator["sites"], function ($el) {
                return count($el) === 1;
            }));

            $siteIds = array_map(function ($el) {
                return (int) $el[1];
            }, array_filter($validator["sites"], function ($el) {
                return count($el) > 1;
            }));

            $localityIdsInSurvey =  $surveyProgram->localities->pluck('id');

            $siteValidator = Validator::validate([
                'localityIds' => $localityIds,
                'siteIds' => $siteIds,
            ], [
                'localityIds.*' => [
                    'required',
                    'integer',
                    Rule::exists('localities', 'id')->where('survey_program_id', $validator["survey_program_id"]),
                ],
                'siteIds.*' => [
                    'required',
                    'integer',
                    Rule::exists('sites', 'id')->whereIn('locality_id', $localityIdsInSurvey),
                ],
            ]);

            if (array_key_exists("categoryIds", $siteValidator)) {
                $newEntry->localities()->attach($siteValidator["localityIds"]);
            }
            if (array_key_exists("categoryIds", $siteValidator)) {
                $newEntry->sites()->attach($siteValidator["siteIds"]);
            }
        }

        if (array_key_exists("taxas", $validator)) {
            $categoryIds = array_map(function ($el) {
                return (int) $el[0];
            }, array_filter($validator["taxas"], function ($el) {
                return count($el) === 1;
            }));

            $taxaIds = array_map(function ($el) {
                return (int) $el[1];
            }, array_filter($validator["taxas"], function ($el) {
                return count($el) > 1;
            }));


            $taxaValidator = Validator::validate([
                'categoryIds' => $categoryIds,
                'taxaIds' => $taxaIds,
            ], [
                'categoryIds.*' => [
                    'required',
                    'integer',
                    Rule::exists('taxa_categories', 'id')->where('survey_program_id', $validator["survey_program_id"]),
                ],
                'taxaIds.*' => [
                    'required',
                    'integer',
                    Rule::exists('taxas', 'id')->where('survey_program_id', $validator["survey_program_id"]),
                ],
            ]);

            if (array_key_exists("taxaIds", $taxaValidator)) {
                $newEntry->taxas()->attach($taxaValidator["taxaIds"]);
            }
            if (array_key_exists("categoryIds", $taxaValidator)) {
                $newEntry->taxaCategories()->attach($taxaValidator["categoryIds"]);
            }
        }

        $filename = $surveyProgram->name . '-' . now()->format('Ymdhis') . '.xlsx';

        SurveyProgramExportJob::dispatch($newEntry, $request->toArray(), $filename);

        return new ExportResource($newEntry);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Export  $export
     * @return \Illuminate\Http\Response
     */
    public function show(Export $export)
    {
        return Storage::download($export->url);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Export  $export
     * @return \Illuminate\Http\Response
     */
    public function update(ExportRequest $request, Export $export)
    {
        $validator = $request->validated();

        $export->update($validator);

        return new ExportResource($export);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Export  $export
     * @return \Illuminate\Http\Response
     */
    public function destroy(Export $export)
    {
        if ($export->url) {
            Storage::delete($export->url);
        }
        $export->delete();


        return response()->json(null, 204);
    }
}
