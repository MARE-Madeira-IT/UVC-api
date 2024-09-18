<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Http\Controllers\Controller;
use App\Http\QueryFilters\TaxaFilters;
use App\Http\Requests\TaxaPhotoRequest;
use App\Http\Requests\TaxaRequest;
use App\Http\Requests\TaxaToggleValidationRequest;
use App\Http\Resources\TaxaCategoryFullResource;
use App\Http\Resources\TaxaResource;
use App\Models\Indicator;
use App\Models\Taxa;
use App\Models\TaxaCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaxaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TaxaFilters $filters)
    {
        return TaxaResource::collection(Taxa::filterBy($filters)->paginate(10));
    }

    public function selector()
    {
        return TaxaCategoryFullResource::collection(TaxaCategory::with('taxas')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaxaRequest $request)
    {
        $validator = $request->validated();

        $newEntry = Taxa::create($validator);

        if (array_key_exists("indicators", $validator)) {
            $indicators = $validator["indicators"];

            foreach ($indicators as $key => $value) {
                $indicator = Indicator::where('name', $key)->where('project_id', $newEntry->project_id)->first();
                $newEntry->indicators()->attach($indicator->id, ["name" => $value]);
            }
        }

        return new TaxaResource($newEntry);
    }

    public function uploadPhoto(Taxa $taxa, TaxaPhotoRequest $request)
    {
        $photo = $request->file('photo');

        $path = Storage::disk('public_folder')->put('taxas/' . ($taxa->category->name ?? 'others'), $photo);

        $taxa->photo_url = $path;
        $taxa->save();

        return new TaxaResource($taxa);
    }

    public function toggleValidation(Taxa $taxa, TaxaToggleValidationRequest $request)
    {
        $validator = $request->validated();
        $taxa->update($validator);


        return new TaxaResource($taxa);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Taxa  $mareTaxa
     * @return \Illuminate\Http\Response
     */
    public function show(Taxa $taxa)
    {
        return new TaxaResource($taxa);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Taxa  $mareTaxa
     * @return \Illuminate\Http\Response
     */
    public function update(Taxa $taxa, TaxaRequest $request)
    {
        $validator = $request->validated();
        $taxa->update($validator);

        $indicators = $validator["indicators"];

        $taxa->indicators()->detach();

        foreach ($indicators as $key => $value) {
            $indicator = Indicator::where('name', $key)->where('project_id', $taxa->project_id)->first();
            $taxa->indicators()->attach($indicator->id, ["name" => $value]);
        }

        return new TaxaResource($taxa);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Taxa  $mareTaxa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Taxa $taxa)
    {
        $taxa->delete();

        return response()->json(null, 204);
    }
}