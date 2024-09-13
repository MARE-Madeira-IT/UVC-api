<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Http\Resources\SizeCategoryResource;
use App\Models\SizeCategory;
use Illuminate\Http\Request;

class SizeCategoryController extends Controller
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
        return SizeCategoryResource::collection(SizeCategory::all());
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
     * @param  \App\SizeCategory  $mareSizeCategory
     * @return \Illuminate\Http\Response
     */
    public function show(SizeCategory $mareSizeCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SizeCategory  $mareSizeCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(SizeCategory $mareSizeCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SizeCategory  $mareSizeCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SizeCategory $mareSizeCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SizeCategory  $mareSizeCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(SizeCategory $mareSizeCategory)
    {
        //
    }
}
