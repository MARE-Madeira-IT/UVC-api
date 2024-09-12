<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\QueryFilters\MareBenthicsFilters;
use App\Http\Requests\MareBenthicRequest;
use App\Http\Resources\MareBenthicGroupedResource;
use App\Http\Resources\MareBenthicResource;
use App\Models\MareBenthic;
use App\Models\MareReport;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class MareBenthicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MareBenthicsFilters $filters)
    {
        return MareBenthicGroupedResource::collection(
            MareReport::filterBy($filters)->whereHas('benthics')->paginate(10)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MareBenthicRequest $request)
    {
        $validator = $request->validated();
        $report = MareReport::findOrFail($validator["report_id"]);

        if ($report->benthics()->exists()) {
            return response()->json(["message" => "Survey already has benthics"], 409);
        }

        DB::beginTransaction();
        foreach ($validator["benthics"] as $benthic) {
            MareBenthic::create([
                "report_id" => $validator["report_id"],
                "substrate_id" => $benthic["substrate_id"],
                "notes" => Arr::get($validator, 'notes'),
                "taxa_id" => array_key_exists("taxa_id", $benthic) ? $benthic["taxa_id"][1] : null,
                "p##" => $benthic["p"],
            ]);
        }

        DB::commit();

        return new MareBenthicGroupedResource(MareReport::find($validator["report_id"]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MareBenthic  $mareBenthic
     * @return \Illuminate\Http\Response
     */
    public function show(MareBenthic $benthic)
    {
        return new MareBenthicResource($benthic);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MareBenthic  $mareBenthic
     * @return \Illuminate\Http\Response
     */
    public function update(MareBenthicRequest $request)
    {

        $validator = $request->validated();
        DB::beginTransaction();

        foreach ($validator["benthics"] as $benthic) {
            $record = MareBenthic::findOrFail($benthic["id"]);
            $record->update([
                "substrate_id" => $benthic["substrate_id"],
                "notes" => Arr::get($benthic, 'notes'),
                "taxa_id" => array_key_exists("taxa_id", $benthic) ? $benthic["taxa_id"][1] : null,
            ]);
        }

        DB::commit();

        return new MareBenthicGroupedResource(MareReport::find($validator["report_id"]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MareBenthic  $mareBenthic
     * @return \Illuminate\Http\Response
     */
    public function destroy($benthic)
    {
        $benthics = MareBenthic::where('report_id', $benthic);
        DB::beginTransaction();
        foreach ($benthics as $cBenthic) {
            $cBenthic->delete();
        }

        DB::commit();
        return response()->json(null, 204);
    }
}
