<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\SiteResource;
use App\Models\Site;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SiteResource::collection(Site::paginate(10));
    }

    public function selector()
    {
        return SiteResource::collection(Site::all());
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
     * @param  \App\Site  $mareSite
     * @return \Illuminate\Http\Response
     */
    public function show(Site $mareSite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Site  $mareSite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Site $mareSite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Site  $mareSite
     * @return \Illuminate\Http\Response
     */
    public function destroy(Site $mareSite)
    {
        //
    }
}
