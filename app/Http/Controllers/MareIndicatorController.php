<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\QueryFilters\MareIndicatorFilters;
use App\Http\Requests\MareIndicatorRequest;
use App\Http\Resources\MareIndicatorResource;
use App\Models\MareIndicator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MareIndicatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MareIndicatorFilters $filters)
    {
        return MareIndicatorResource::collection(MareIndicator::filterBy($filters)->paginate(10));
    }

    public function selector(MareIndicatorFilters $filters)
    {
        return MareIndicatorResource::collection(MareIndicator::filterBy($filters)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MareIndicatorRequest $request)
    {
        $validator = $request->validated();
        DB::beginTransaction();

        $newEntry = MareIndicator::create($validator);

        DB::commit();

        return new MareIndicatorResource($newEntry);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MareIndicator  $mareIndicator
     * @return \Illuminate\Http\Response
     */
    public function show(MareIndicator $indicator)
    {
        return new MareIndicatorResource($indicator);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MareIndicator  $mareIndicator
     * @return \Illuminate\Http\Response
     */
    public function update(MareIndicatorRequest $request, MareIndicator $indicator)
    {
        $validator = $request->validated();
        DB::beginTransaction();

        $indicator->update($validator);


        DB::commit();

        return new MareIndicatorResource($indicator);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MareIndicator  $mareIndicator
     * @return \Illuminate\Http\Response
     */
    public function destroy(MareIndicator $indicator)
    {
        $indicator->delete();

        return response()->json(null, 204);
    }
}
