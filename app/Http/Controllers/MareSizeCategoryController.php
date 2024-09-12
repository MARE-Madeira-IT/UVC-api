<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Http\Resources\MareSizeCategoryResource;
use App\Models\MareSizeCategory;
use Illuminate\Http\Request;

class MareSizeCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    public function selector()
    {
        return MareSizeCategoryResource::collection(MareSizeCategory::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\MareSizeCategory  $mareSizeCategory
     * @return \Illuminate\Http\Response
     */
    public function show(MareSizeCategory $mareSizeCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MareSizeCategory  $mareSizeCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(MareSizeCategory $mareSizeCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MareSizeCategory  $mareSizeCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MareSizeCategory $mareSizeCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MareSizeCategory  $mareSizeCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(MareSizeCategory $mareSizeCategory)
    {
        //
    }
}
