<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\QueryFilters\MareDepthFilters;
use App\Http\Requests\MareDepthRequest;
use App\Http\Resources\MareDepthResource;
use App\Models\MareDepth;
use Illuminate\Http\Request;

class MareDepthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MareDepthFilters $filters)
    {
        return MareDepthResource::collection(MareDepth::filterBy($filters)->paginate(10));
    }

    public function selector()
    {
        return MareDepthResource::collection(MareDepth::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MareDepthRequest $request)
    {
        $validator = $request->validated();

        $newEntry = MareDepth::create($validator);

        return new MareDepthResource($newEntry);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MareDepth  $depth
     * @return \Illuminate\Http\Response
     */
    public function show(MareDepth $depth)
    {
        return new MareDepthResource($depth);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MareDepth  $mareDepth
     * @return \Illuminate\Http\Response
     */
    public function update(MareDepthRequest $request, MareDepth $depth)
    {
        $validator = $request->validated();

        $depth->update($validator);

        return new MareDepthResource($depth);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MareDepth  $mareDepth
     * @return \Illuminate\Http\Response
     */
    public function destroy(MareDepth $depth)
    {
        $depth->delete();

        return response()->json(null, 204);
    }
}
