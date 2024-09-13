<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\QueryFilters\IndicatorFilters;
use App\Http\Requests\IndicatorRequest;
use App\Http\Resources\IndicatorResource;
use App\Models\Indicator;
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


        DB::commit();

        return new IndicatorResource($indicator);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Indicator  $mareIndicator
     * @return \Illuminate\Http\Response
     */
    public function destroy(Indicator $indicator)
    {
        $indicator->delete();

        return response()->json(null, 204);
    }
}
