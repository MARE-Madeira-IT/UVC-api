<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\QueryFilters\IndicatorFilters;
use App\Http\Requests\IndicatorRequest;
use App\Http\Resources\IndicatorResource;
use App\Models\Indicator;
use App\Models\IndicatorHasValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndicatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndicatorFilters $filters)
    {
        return IndicatorResource::collection(Indicator::filterBy($filters)->paginate(10));
    }

    public function selector(IndicatorFilters $filters)
    {
        return IndicatorResource::collection(Indicator::filterBy($filters)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(IndicatorRequest $request)
    {
        $validator = $request->validated();
        DB::beginTransaction();

        $newEntry = Indicator::create($validator);

        if (array_key_exists("values", $validator) && count($validator["values"]) > 0) {
            foreach ($validator["values"] as $value) {
                IndicatorHasValue::create([
                    "name" => $value,
                    "indicator_id" => $newEntry->id,
                ]);
            }
        }

        DB::commit();

        return new IndicatorResource($newEntry);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Indicator  $mareIndicator
     * @return \Illuminate\Http\Response
     */
    public function show(Indicator $indicator)
    {
        return new IndicatorResource($indicator);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Indicator  $mareIndicator
     * @return \Illuminate\Http\Response
     */
    public function update(IndicatorRequest $request, Indicator $indicator)
    {
        $validator = $request->validated();
        DB::beginTransaction();

        $indicator->update($validator);


        if (array_key_exists("values", $validator) && count($validator["values"]) > 0) {
            IndicatorHasValue::whereNotIn('name', $validator["values"])->where('indicator_id', $indicator->id)->delete();

            $indicatorValues = $indicator->indicatorValues->pluck("name")->toArray();
            foreach ($validator["values"] as $value) {
                if (!in_array($value, $indicatorValues)) {
                    $indicator->indicatorValues()->create([
                        "name" => $value,
                    ]);
                }
            }
        }

        logger(IndicatorHasValue::where('indicator_id', $indicator->id)->get()->pluck("name"));


        DB::commit();

        return new IndicatorResource($indicator->fresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Indicator  $mareIndicator
     * @return \Illuminate\Http\Response
     */
    public function destroy(Indicator $indicator)
    {
        $indicator->indicatorValues()->delete();
        $indicator->delete();

        return response()->json(null, 204);
    }
}
