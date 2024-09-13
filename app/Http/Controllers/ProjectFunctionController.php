<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\QueryFilters\ProjectFunctionFilters;
use App\Http\Requests\ProjectFunctionRequest;
use App\Http\Resources\ProjectFunctionResource;
use App\Models\ProjectFunction;

class ProjectFunctionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectFunctionFilters $filters)
    {
        return ProjectFunctionResource::collection(ProjectFunction::filterBy($filters)->paginate(10));
    }

    public function selector(ProjectFunctionFilters $filters)
    {
        return ProjectFunctionResource::collection(ProjectFunction::filterBy($filters)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectFunctionRequest $request)
    {
        $validator = $request->validated();

        $newEntry = ProjectFunction::create($validator);

        return new ProjectFunctionResource($newEntry);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProjectFunction  $mareFunction
     * @return \Illuminate\Http\Response
     */
    public function show(ProjectFunction $function)
    {
        return new ProjectFunctionResource($function);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProjectFunction  $mareFunction
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectFunctionRequest $request, ProjectFunction $function)
    {
        $validator = $request->validated();

        $function->update($validator);

        return new ProjectFunctionResource($function);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProjectFunction  $mareFunction
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProjectFunction $function)
    {
        $function->delete();

        return response()->json(null, 204);
    }
}
