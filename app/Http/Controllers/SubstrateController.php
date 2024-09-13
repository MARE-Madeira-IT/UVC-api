<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\QueryFilters\SubstrateFilters;
use App\Http\Resources\SubstrateResource;
use App\Models\Substrate;
use Illuminate\Http\Request;

class SubstrateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SubstrateFilters $filters)
    {
        return SubstrateResource::collection(Substrate::filterBy($filters)->paginate(10));
    }

    public function selector()
    {
        return SubstrateResource::collection(Substrate::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Substrate  $mareSubstrate
     * @return \Illuminate\Http\Response
     */
    public function show(Substrate $mareSubstrate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Substrate  $mareSubstrate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Substrate $mareSubstrate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Substrate  $mareSubstrate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Substrate $mareSubstrate)
    {
        //
    }
}
