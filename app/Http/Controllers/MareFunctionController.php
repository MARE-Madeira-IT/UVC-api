<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\QueryFilters\MareFunctionFilters;
use App\Http\Requests\MareFunctionRequest;
use App\Http\Resources\MareFunctionResource;
use App\Models\MareFunction;
use Illuminate\Http\Request;

class MareFunctionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MareFunctionFilters $filters)
    {
        return MareFunctionResource::collection(MareFunction::filterBy($filters)->paginate(10));
    }

    public function selector(MareFunctionFilters $filters)
    {
        return MareFunctionResource::collection(MareFunction::filterBy($filters)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MareFunctionRequest $request)
    {
        $validator = $request->validated();

        $newEntry = MareFunction::create($validator);

        return new MareFunctionResource($newEntry);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MareFunction  $mareFunction
     * @return \Illuminate\Http\Response
     */
    public function show(MareFunction $function)
    {
        return new MareFunctionResource($function);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MareFunction  $mareFunction
     * @return \Illuminate\Http\Response
     */
    public function update(MareFunctionRequest $request, MareFunction $function)
    {
        $validator = $request->validated();

        $function->update($validator);

        return new MareFunctionResource($function);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MareFunction  $mareFunction
     * @return \Illuminate\Http\Response
     */
    public function destroy(MareFunction $function)
    {
        $function->delete();

        return response()->json(null, 204);
    }
}
