<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\QueryFilters\MotileFilters;
use App\Http\Requests\MotileRequest;
use App\Http\Resources\MotileGroupedResource;
use App\Http\Resources\MotileResource;
use App\Models\Motile;
use App\Models\ReportMotile;
use App\Models\Taxa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MotileController extends Controller
{
    /**
     * Display a listing of the resource.
     *\\\
     * @return \Illuminate\Http\Response
     */
    public function index(MotileFilters $filters)
    {
        return MotileGroupedResource::collection(
            ReportMotile::filterBy($filters)->whereHas('motiles')->paginate(10)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MotileRequest $request)
    {
        $validator = $request->validated();
        DB::beginTransaction();

        $mareReportMotile = ReportMotile::updateOrCreate([
            "report_id" => $validator["report_id"],
            "type" => $validator["type"]
        ], [
            "report_id" => $validator["report_id"],
            "type" => $validator["type"],
        ]);


        foreach ($validator["motiles"] as $motile) {
            $taxa = Taxa::findOrFail($motile["taxa_id"]);
            $aIndicator = $taxa->indicators()->where('indicators.name', 'a')->first();
            $bIndicator = $taxa->indicators()->where('indicators.name', 'b')->first();

            $biomass = null;
            $density = null;

            if (isset($aIndicator)) {
                $aValue = (float) $aIndicator->pivot->name;
            }

            if (isset($bIndicator)) {
                $bValue = (float) $bIndicator->pivot->name;
            }

            if (isset($aValue) && isset($bValue) && isset($motile["size"])) {
                $biomass = $aValue * pow((float) $motile["size"], $bValue);
            }

            if (isset($motile["ntotal"]) && isset($mareReportMotile->report->surveyed_area)) {
                $density = (float) $motile["ntotal"] / $mareReportMotile->report->surveyed_area;
            }

            Motile::create([
                "report_motile_id" => $mareReportMotile->id,
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

        return new MotileGroupedResource($mareReportMotile);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Motile  $mareMotile
     * @return \Illuminate\Http\Response
     */
    public function show(ReportMotile $mareReportMotileId)
    {
        return new MotileGroupedResource($mareReportMotileId);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Motile  $mareMotile
     * @return \Illuminate\Http\Response
     */
    public function update(MotileRequest $request, $mareReportMotileId)
    {
        $validator = $request->validated();
        DB::beginTransaction();

        $mareReportMotile = ReportMotile::findOrFail($mareReportMotileId);

        $mareReportMotile->motiles()->delete();

        foreach ($validator["motiles"] as $motile) {
            // $density =
            $taxa = Taxa::findOrFail($motile["taxa_id"]);
            $aIndicator = $taxa->indicators()->where('indicators.name', 'a')->first();
            $bIndicator = $taxa->indicators()->where('indicators.name', 'b')->first();

            $biomass = null;
            $density = null;

            if (isset($aIndicator)) {
                $aValue = (float) $aIndicator->pivot->name;
            }

            if (isset($bIndicator)) {
                $bValue = (float) $bIndicator->pivot->name;
            }

            if (isset($aValue) && isset($bValue) && isset($motile["size"])) {
                $biomass = $aValue * pow((float) $motile["size"], $bValue);
            }

            if (isset($motile["ntotal"]) && isset($mareReportMotile->report->surveyed_area)) {
                $density = (float) $motile["ntotal"] / $mareReportMotile->report->surveyed_area;
            }

            Motile::create([
                "report_motile_id" => $mareReportMotile->id,
                "taxa_id" => $motile["taxa_id"],
                "size_category_id" => $motile["size_category_id"] ?? null,
                "size" => $motile["size"] ?? null,
                'density/1' => $density,
                'biomass/1' => $biomass,
                "ntotal" => $motile["ntotal"],
                "notes" => $motile["notes"] ?? '',
            ]);
        }


        DB::commit();

        return new MotileGroupedResource($mareReportMotile);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Motile  $mareMotile
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReportMotile $mareReportMotileId)
    {
        DB::beginTransaction();

        $mareReportMotileId->motiles()->delete();
        $mareReportMotileId->delete();
        DB::commit();
        return response()->json(null, 204);
    }
}
