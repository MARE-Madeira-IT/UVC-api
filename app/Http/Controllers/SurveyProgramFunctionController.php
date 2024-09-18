<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\QueryFilters\SurveyProgramFunctionFilters;
use App\Http\Requests\SurveyProgramFunctionRequest;
use App\Http\Resources\SurveyProgramFunctionResource;
use App\Models\SurveyProgramFunction;

class SurveyProgramFunctionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SurveyProgramFunctionFilters $filters)
    {
        return SurveyProgramFunctionResource::collection(SurveyProgramFunction::filterBy($filters)->paginate(10));
    }

    public function selector(SurveyProgramFunctionFilters $filters)
    {
        return SurveyProgramFunctionResource::collection(SurveyProgramFunction::filterBy($filters)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SurveyProgramFunctionRequest $request)
    {
        $validator = $request->validated();

        $newEntry = SurveyProgramFunction::create($validator);

        return new SurveyProgramFunctionResource($newEntry);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SurveyProgramFunction  $mareFunction
     * @return \Illuminate\Http\Response
     */
    public function show(SurveyProgramFunction $function)
    {
        return new SurveyProgramFunctionResource($function);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SurveyProgramFunction  $mareFunction
     * @return \Illuminate\Http\Response
     */
    public function update(SurveyProgramFunctionRequest $request, SurveyProgramFunction $function)
    {
        $validator = $request->validated();

        $function->update($validator);

        return new SurveyProgramFunctionResource($function);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SurveyProgramFunction  $mareFunction
     * @return \Illuminate\Http\Response
     */
    public function destroy(SurveyProgramFunction $function)
    {
        $function->delete();

        return response()->json(null, 204);
    }
}
