<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Http\Resources\MareTaxaCategoryResource;
use App\Models\MareTaxaCategory;
use Illuminate\Http\Request;

class MareTaxaCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MareTaxaCategoryResource::collection(MareTaxaCategory::paginate(10));
    }

    public function selector()
    {
        return MareTaxaCategoryResource::collection(MareTaxaCategory::all());
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
     * @param  \App\MareTaxaCategory  $mareTaxaCategory
     * @return \Illuminate\Http\Response
     */
    public function show(MareTaxaCategory $mareTaxaCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MareTaxaCategory  $mareTaxaCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(MareTaxaCategory $mareTaxaCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MareTaxaCategory  $mareTaxaCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MareTaxaCategory $mareTaxaCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MareTaxaCategory  $mareTaxaCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(MareTaxaCategory $mareTaxaCategory)
    {
        //
    }
}
