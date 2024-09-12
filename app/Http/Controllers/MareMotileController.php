<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\QueryFilters\MareMotileFilters;
use App\Http\Requests\MareMotileRequest;
use App\Http\Resources\MareMotileGroupedResource;
use App\Http\Resources\MareMotileResource;
use App\Models\MareMotile;
use App\Models\MareReportMotile;
use App\Models\MareTaxa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MareMotileController extends Controller
{
    /**
     * Display a listing of the resource.
     *\\\
     * @return \Illuminate\Http\Response
     */
    public function index(MareMotileFilters $filters)
    {
        return MareMotileGroupedResource::collection(
            MareReportMotile::filterBy($filters)->whereHas('motiles')->paginate(10)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MareMotileRequest $request)
    {
        $validator = $request->validated();
        DB::beginTransaction();

        $mareReportMotile = MareReportMotile::updateOrCreate([
            "report_id" => $validator["report_id"],
            "type" => $validator["type"]
        ], [
            "report_id" => $validator["report_id"],
            "type" => $validator["type"],
        ]);


        foreach ($validator["motiles"] as $motile) {
            // $density =
            $taxa = MareTaxa::findOrFail($motile["taxa_id"]);
            $aIndicator = $taxa->indicators()->where('mare_indicators.name', 'a')->first();
            $bIndicator = $taxa->indicators()->where('mare_indicators.name', 'b')->first();

            $biomass = null;
            $density = null;

            if (isset($aIndicator)) {
                $aValue = $aIndicator->pivot->name;
            }

            if (isset($bIndicator)) {
                $bValue = $bIndicator->pivot->name;
            }

            if (isset($aValue) && isset($bValue)) {
                $aValue * pow($motile["size"], $bValue);
            }

            if (isset($biomass)) {
                $motile["ntotal"] / $mareReportMotile->report->surveyed_area;
            }


            MareMotile::create([
                "mare_report_motile_id" => $mareReportMotile->id,
                "taxa_id" => $motile["taxa_id"],
                "size_category_id" => $motile["size_category_id"] ?? null,
                "size" => $motile["size"] ?? null,
                'density/1' => $density,
                'biomass/1' => $biomass,
                "ntotal" => $motile["ntotal"],
                "notes" => $motile["notes"] ?? "",
            ]);
        }

        DB::commit();

        return new MareMotileGroupedResource($mareReportMotile);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MareMotile  $mareMotile
     * @return \Illuminate\Http\Response
     */
    public function show(MareMotile $motile)
    {
        return new MareMotileResource($motile);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MareMotile  $mareMotile
     * @return \Illuminate\Http\Response
     */
    public function update(MareMotileRequest $request, $mareReportMotileId)
    {
        $validator = $request->validated();
        DB::beginTransaction();

        $mareReportMotile = MareReportMotile::findOrFail($mareReportMotileId);

        $mareReportMotile->motiles()->delete();

        foreach ($validator["motiles"] as $motile) {
            // $density =
            $taxa = MareTaxa::findOrFail($motile["taxa_id"]);
            $aIndicator = $taxa->indicators()->where('mare_indicators.name', 'a')->first();
            $bIndicator = $taxa->indicators()->where('mare_indicators.name', 'b')->first();

            $biomass = "N/A";
            $density = "N/A";

            if (isset($aIndicator)) {
                $aValue = $aIndicator->pivot->name;
            }

            if (isset($bIndicator)) {
                $bValue = $bIndicator->pivot->name;
            }


            $biomass = isset($aValue) && isset($bValue) ? $aValue * pow($motile["size"], $bValue) : "N/A";
            $density = isset($biomass) ? $motile["ntotal"] / $mareReportMotile->report->surveyed_area : "N/A";

            MareMotile::create([
                "mare_report_motile_id" => $mareReportMotile->id,
                "taxa_id" => $motile["taxa_id"],
                "size_category_id" => $motile["size_category_id"] ?? null,
                "size" => $motile["size"] ?? null,
                'density/1' => $density,
                'biomass/1' => $biomass,
                "ntotal" => $motile["ntotal"],
                "notes" => $motile["notes"],
            ]);
        }


        DB::commit();

        return new MareMotileGroupedResource($mareReportMotile);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MareMotile  $mareMotile
     * @return \Illuminate\Http\Response
     */
    public function destroy(MareReportMotile $mareReportMotileId)
    {
        DB::beginTransaction();

        $mareReportMotileId->motiles()->delete();
        $mareReportMotileId->delete();
        DB::commit();
        return response()->json(null, 204);
    }
}
