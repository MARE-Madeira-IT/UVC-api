<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\QueryFilters\LocalityFilters;
use App\Http\Requests\LocalityRequest;
use App\Http\Resources\LocalityResource;
use App\Models\Locality;
use App\Models\Site;
use Illuminate\Support\Facades\DB;

class LocalityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(LocalityFilters $filters)
    {
        return LocalityResource::collection(Locality::filterBy($filters)->paginate(10));
    }

    public function selector(LocalityFilters $filters)
    {
        return LocalityResource::collection(Locality::filterBy($filters)->get());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LocalityRequest $request)
    {
        $validator = $request->validated();
        DB::beginTransaction();

        $newEntry = Locality::create($validator);

        foreach ($validator["sites"] as $key => $site) {
            Site::create([
                "name" => $site["name"],
                "code" => $site["code"],
                "locality_id" => $newEntry->id,
            ]);
        }

        DB::commit();

        return new LocalityResource($newEntry);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Locality  $locality
     * @return \Illuminate\Http\Response
     */
    public function show(Locality $locality)
    {
        return new LocalityResource($locality);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Locality  $locality
     * @return \Illuminate\Http\Response
     */
    public function update(LocalityRequest $request, Locality $locality)
    {
        $validator = $request->validated();
        DB::beginTransaction();

        $locality->update($validator);

        foreach ($validator["sites"] as $key => $site) {
            Site::updateOrCreate(["id" => $site["id"] ?? null], [
                "name" => $site["name"],
                "code" => $site["code"],
                "latitude" => $site["latitude"],
                "longitude" => $site["longitude"],
                "locality_id" => $locality->id,
            ]);
        }
        if (array_key_exists("removeIDs", $validator)) {
            $removeSites = Site::whereIn('id', $validator["removeIDs"])->get();

            foreach ($removeSites as $key => $remove) {
                $remove->delete();
            }
        }
        DB::commit();

        return new LocalityResource($locality);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Locality  $locality
     * @return \Illuminate\Http\Response
     */
    public function destroy(Locality $locality)
    {
        $locality->delete();

        return response()->json(null, 204);
    }
}
