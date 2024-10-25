<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\QueryFilters\TaxaCategoryFilters;
use App\Http\Requests\TaxaCategoryRequest;
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
    public function index(TaxaCategoryFilters $filters)
    {
        return TaxaCategoryResource::collection(TaxaCategory::filterBy($filters)->paginate(10));
    }

    public function selector(TaxaCategoryFilters $filters)
    {
        return TaxaCategoryResource::collection(TaxaCategory::filterBy($filters)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaxaCategoryRequest $request)
    {
        $validator = $request->validated();

        $newEntry = TaxaCategory::create($validator);

        return new TaxaCategoryResource($newEntry);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\TaxaCategory  $mareTaxaCategory
     * @return \Illuminate\Http\Response
     */
    public function show(TaxaCategory $taxaCategory)
    {
        return new TaxaCategoryResource($taxaCategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TaxaCategory  $mareTaxaCategory
     * @return \Illuminate\Http\Response
     */
    public function update(TaxaCategoryRequest $request, TaxaCategory $taxaCategory)
    {
        $validator = $request->validated();

        $taxaCategory->update($validator);

        return new TaxaCategoryResource($taxaCategory);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TaxaCategory  $mareTaxaCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(TaxaCategory $taxaCategory)
    {
        $taxaCategory->delete();

        return response()->json(null, 204);
    }
}
