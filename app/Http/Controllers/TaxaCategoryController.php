<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Http\Resources\TaxaCategoryResource;
use App\Models\TaxaCategory;
use Illuminate\Http\Request;

class TaxaCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TaxaCategoryResource::collection(TaxaCategory::paginate(10));
    }

    public function selector()
    {
        return TaxaCategoryResource::collection(TaxaCategory::all());
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
     * @param  \App\TaxaCategory  $mareTaxaCategory
     * @return \Illuminate\Http\Response
     */
    public function show(TaxaCategory $mareTaxaCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TaxaCategory  $mareTaxaCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(TaxaCategory $mareTaxaCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TaxaCategory  $mareTaxaCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TaxaCategory $mareTaxaCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TaxaCategory  $mareTaxaCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(TaxaCategory $mareTaxaCategory)
    {
        //
    }
}
