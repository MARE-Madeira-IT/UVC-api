<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Http\Controllers\Controller;
use App\Http\QueryFilters\MareTaxaFilters;
use App\Http\Requests\MareTaxaPhotoRequest;
use App\Http\Requests\MareTaxaRequest;
use App\Http\Requests\MareTaxaToggleValidationRequest;
use App\Http\Resources\MareTaxaCategoryFullResource;
use App\Http\Resources\MareTaxaResource;
use App\Models\MareIndicator;
use App\Models\MareTaxa;
use App\Models\MareTaxaCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MareTaxaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MareTaxaFilters $filters)
    {
        return MareTaxaResource::collection(MareTaxa::filterBy($filters)->paginate(10));
    }

    public function selector()
    {
        return MareTaxaCategoryFullResource::collection(MareTaxaCategory::with('taxas')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MareTaxaRequest $request)
    {
        $validator = $request->validated();

        $newEntry = MareTaxa::create($validator);

        if (array_key_exists("indicators", $validator)) {
            $indicators = $validator["indicators"];

            foreach ($indicators as $key => $value) {
                $indicator = MareIndicator::where('name', $key)->where('project_id', $newEntry->project_id)->first();
                $newEntry->indicators()->attach($indicator->id, ["name" => $value]);
            }
        }

        return new MareTaxaResource($newEntry);
    }

    public function uploadPhoto(MareTaxa $taxa, MareTaxaPhotoRequest $request)
    {
        $photo = $request->file('photo');

        $path = Storage::disk('public_folder')->put('taxas/' . ($taxa->category->name ?? 'others'), $photo);

        $taxa->photo_url = $path;
        $taxa->save();

        return new MareTaxaResource($taxa);
    }

    public function toggleValidation(MareTaxa $taxa, MareTaxaToggleValidationRequest $request)
    {
        $validator = $request->validated();
        $taxa->update($validator);


        return new MareTaxaResource($taxa);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MareTaxa  $mareTaxa
     * @return \Illuminate\Http\Response
     */
    public function show(MareTaxa $taxa)
    {
        return new MareTaxaResource($taxa);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MareTaxa  $mareTaxa
     * @return \Illuminate\Http\Response
     */
    public function update(MareTaxa $taxa, MareTaxaRequest $request)
    {
        $validator = $request->validated();
        $taxa->update($validator);

        $indicators = $validator["indicators"];

        $taxa->indicators()->detach();

        foreach ($indicators as $key => $value) {
            $indicator = MareIndicator::where('name', $key)->where('project_id', $taxa->project_id)->first();
            $taxa->indicators()->attach($indicator->id, ["name" => $value]);
        }

        return new MareTaxaResource($taxa);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MareTaxa  $mareTaxa
     * @return \Illuminate\Http\Response
     */
    public function destroy(MareTaxa $taxa)
    {
        $taxa->delete();

        return response()->json(null, 204);
    }
}
