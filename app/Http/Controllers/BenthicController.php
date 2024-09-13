<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\QueryFilters\BenthicsFilters;
use App\Http\Requests\BenthicRequest;
use App\Http\Resources\BenthicGroupedResource;
use App\Http\Resources\BenthicResource;
use App\Models\Benthic;
use App\Models\Report;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class BenthicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BenthicsFilters $filters)
    {
        return BenthicGroupedResource::collection(
            Report::filterBy($filters)->whereHas('benthics')->paginate(10)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BenthicRequest $request)
    {
        $validator = $request->validated();
        $report = Report::findOrFail($validator["report_id"]);

        if ($report->benthics()->exists()) {
            return response()->json(["message" => "Survey already has benthics"], 409);
        }

        DB::beginTransaction();
        foreach ($validator["benthics"] as $benthic) {
            Benthic::create([
                "report_id" => $validator["report_id"],
                "substrate_id" => $benthic["substrate_id"],
                "notes" => Arr::get($validator, 'notes'),
                "taxa_id" => array_key_exists("taxa_id", $benthic) ? $benthic["taxa_id"][1] : null,
                "p##" => $benthic["p"],
            ]);
        }

        DB::commit();

        return new BenthicGroupedResource(Report::find($validator["report_id"]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Benthic  $mareBenthic
     * @return \Illuminate\Http\Response
     */
    public function show(Benthic $benthic)
    {
        return new BenthicResource($benthic);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Benthic  $mareBenthic
     * @return \Illuminate\Http\Response
     */
    public function update(BenthicRequest $request)
    {

        $validator = $request->validated();
        DB::beginTransaction();

        foreach ($validator["benthics"] as $benthic) {
            $record = Benthic::findOrFail($benthic["id"]);
            $record->update([
                "substrate_id" => $benthic["substrate_id"],
                "notes" => Arr::get($benthic, 'notes'),
                "taxa_id" => array_key_exists("taxa_id", $benthic) ? $benthic["taxa_id"][1] : null,
            ]);
        }

        DB::commit();

        return new BenthicGroupedResource(Report::find($validator["report_id"]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Benthic  $mareBenthic
     * @return \Illuminate\Http\Response
     */
    public function destroy($benthic)
    {
        $benthics = Benthic::where('report_id', $benthic);
        DB::beginTransaction();
        foreach ($benthics as $cBenthic) {
            $cBenthic->delete();
        }

        DB::commit();
        return response()->json(null, 204);
    }
}
