<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\MareSiteResource;
use App\Models\MareSite;
use Illuminate\Http\Request;

class MareSiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MareSiteResource::collection(MareSite::paginate(10));
    }

    public function selector()
    {
        return MareSiteResource::collection(MareSite::all());
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
     * @param  \App\MareSite  $mareSite
     * @return \Illuminate\Http\Response
     */
    public function show(MareSite $mareSite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MareSite  $mareSite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MareSite $mareSite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MareSite  $mareSite
     * @return \Illuminate\Http\Response
     */
    public function destroy(MareSite $mareSite)
    {
        //
    }
}
