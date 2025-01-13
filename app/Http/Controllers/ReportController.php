<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\QueryFilters\ReportFilters;
use App\Http\Requests\ReportRequest;
use App\Http\Resources\ReportResource;
use App\Models\Report;
use App\Models\ReportHasFunction;
use App\Models\Site;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ReportFilters $filters)
    {
        return ReportResource::collection(Report::filterBy($filters)->orderBy('date')->paginate(10));
    }

    public function selector(ReportFilters $filters)
    {
        return ReportResource::collection(Report::filterBy($filters)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReportRequest $request)
    {
        $validator = $request->validated();
        $newEntry = Report::create([
            "survey_program_id" => $validator["survey_program_id"],
            "time" => $validator["time"],
            // "code" => $validator["code"],
            "date" => Carbon::parse($validator["date"]),
            "transect" => $validator["transect"],
            "daily_dive" => $validator["daily_dive"],
            "replica" => $validator["replica"],
            "latitude" => Site::findOrFail($validator["site_id"])->latitude,
            "longitude" => Site::findOrFail($validator["site_id"])->longitude,
            "heading" => Arr::get($validator, "heading"),
            "heading_direction" =>  Arr::get($validator, "heading_direction"),
            "site_area" =>  Arr::get($validator, "site_area"),
            "distance" =>  Arr::get($validator, "distance"),
            "site_id" => $validator["site_id"],
            "depth_id" => $validator["depth_id"],
            'surveyed_area' => $validator["surveyed_area"],
        ]);


        if (array_key_exists("functions", $validator)) {
            foreach ($validator["functions"] as $function) {
                ReportHasFunction::create([
                    'function_id' => $function["function_id"],
                    'report_id' => $newEntry->id,
                    'user' => $function["value"],
                ]);
            }
        }

        return new ReportResource($newEntry);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Report  $mareReport
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        return new ReportResource($report);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Report  $mareReport
     * @return \Illuminate\Http\Response
     */
    public function update(ReportRequest $request, Report $report)
    {
        $validator = $request->validated();
        $report->update($validator);

        if (array_key_exists("functions", $validator)) {
            foreach ($validator["functions"] as $function) {
                ReportHasFunction::updateOrCreate([
                    'function_id' => $function["function_id"],
                    'report_id' => $report->id,
                ], [
                    'function_id' => $function["function_id"],
                    'report_id' => $report->id,
                    'user' => $function["value"],
                ]);
            }
        }

        return new ReportResource($report);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Report  $mareReport
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        $report->delete();

        return response()->json(null, 204);
    }
}
