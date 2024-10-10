<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\QueryFilters\DepthFilters;
use App\Http\Requests\DepthRequest;
use App\Http\Resources\DepthResource;
use App\Models\Depth;
use Illuminate\Http\Request;

class DepthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DepthFilters $filters)
    {
        return DepthResource::collection(Depth::orderBy("code")->filterBy($filters)->paginate(10));
    }

    public function selector(DepthFilters $filters)
    {
        return DepthResource::collection(Depth::orderBy("code")->filterBy($filters)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepthRequest $request)
    {
        $validator = $request->validated();

        $lastDepth = Depth::where("survey_program_id", $validator["survey_program_id"])->max("code");

        $newEntry = Depth::create([
            "name" => $validator["name"],
            "survey_program_id" => $validator["survey_program_id"],
            "code" => $lastDepth ? $lastDepth + 1 : 1
        ]);

        return new DepthResource($newEntry);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Depth  $depth
     * @return \Illuminate\Http\Response
     */
    public function show(Depth $depth)
    {
        return new DepthResource($depth);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Depth  $mareDepth
     * @return \Illuminate\Http\Response
     */
    public function update(DepthRequest $request, Depth $depth)
    {
        $validator = $request->validated();

        $depth->update($validator);

        return new DepthResource($depth);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Depth  $mareDepth
     * @return \Illuminate\Http\Response
     */
    public function destroy(Depth $depth)
    {
        $depth->delete();

        return response()->json(null, 204);
    }
}
