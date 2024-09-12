<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\QueryFilters\MareReportFilters;
use App\Http\Requests\MareReportRequest;
use App\Http\Resources\MareReportResource;
use App\Models\MareReport;
use App\Models\MareReportHasFunction;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class MareReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MareReportFilters $filters)
    {
        return MareReportResource::collection(MareReport::filterBy($filters)->paginate(10));
    }

    public function selector(MareReportFilters $filters)
    {
        return MareReportResource::collection(MareReport::filterBy($filters)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MareReportRequest $request)
    {
        $validator = $request->validated();
        #return $validator;
        $newEntry = MareReport::create([
            "project_id" => $validator["project_id"],

            "code" => $validator["code"],
            "date" => $validator["date"],
            "transect" => $validator["transect"],
            "daily_dive" => $validator["daily_dive"],
            "replica" => $validator["replica"],
            "latitude" => $validator["latitude"],
            "longitude" => $validator["longitude"],
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
                MareReportHasFunction::create([
                    'function_id' => $function["function_id"],
                    'report_id' => $newEntry->id,
                    'user' => $function["value"],
                ]);
            }
        }

        return new MareReportResource($newEntry);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MareReport  $mareReport
     * @return \Illuminate\Http\Response
     */
    public function show(MareReport $report)
    {
        return new MareReportResource($report);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MareReport  $mareReport
     * @return \Illuminate\Http\Response
     */
    public function update(MareReportRequest $request, MareReport $report)
    {
        $validator = $request->validated();
        $report->update($validator);

        if (array_key_exists("functions", $validator)) {
            foreach ($validator["functions"] as $function) {
                MareReportHasFunction::updateOrCreate([
                    'function_id' => $function["function_id"],
                    'report_id' => $report->id,
                ], [
                    'function_id' => $function["function_id"],
                    'report_id' => $report->id,
                    'user' => $function["value"],
                ]);
            }
        }

        return new MareReportResource($report);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MareReport  $mareReport
     * @return \Illuminate\Http\Response
     */
    public function destroy(MareReport $report)
    {
        $report->delete();

        return response()->json(null, 204);
    }
}
