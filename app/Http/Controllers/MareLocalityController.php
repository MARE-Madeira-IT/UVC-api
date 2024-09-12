<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\QueryFilters\MareLocalityFilters;
use App\Http\Requests\MareLocalityRequest;
use App\Http\Resources\MareLocalityResource;
use App\Models\MareLocality;
use App\Models\MareSite;
use Illuminate\Support\Facades\DB;

class MareLocalityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MareLocalityFilters $filters)
    {
        return MareLocalityResource::collection(MareLocality::filterBy($filters)->paginate(10));
    }

    public function selector(MareLocalityFilters $filters)
    {
        return MareLocalityResource::collection(MareLocality::filterBy($filters)->get());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MareLocalityRequest $request)
    {
        $validator = $request->validated();
        DB::beginTransaction();

        $newEntry = MareLocality::create($validator);

        foreach ($validator["sites"] as $key => $site) {
            MareSite::create([
                "name" => $site["name"],
                "code" => $site["code"],
                "locality_id" => $newEntry->id,
            ]);
        }

        DB::commit();

        return new MareLocalityResource($newEntry);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MareLocality  $locality
     * @return \Illuminate\Http\Response
     */
    public function show(MareLocality $locality)
    {
        return new MareLocalityResource($locality);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MareLocality  $locality
     * @return \Illuminate\Http\Response
     */
    public function update(MareLocalityRequest $request, MareLocality $locality)
    {
        $validator = $request->validated();
        DB::beginTransaction();

        $locality->update($validator);

        foreach ($validator["sites"] as $key => $site) {
            if (array_key_exists("id", $site)) {
                $editSite = MareSite::find($site["id"]);
                $editSite->update([
                    "name" => $site["name"],
                    "code" => $site["code"]
                ]);
            } else {
                MareSite::create([
                    "name" => $site["name"],
                    "code" => $site["code"],
                    "locality_id" => $locality->id,
                ]);
            }
        }
        if (array_key_exists("removeIDs", $validator)) {
            $removeSites = MareSite::whereIn('id', $validator["removeIDs"])->get();

            foreach ($removeSites as $key => $remove) {
                $remove->delete();
            }
        }
        DB::commit();

        return new MareLocalityResource($locality);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MareLocality  $locality
     * @return \Illuminate\Http\Response
     */
    public function destroy(MareLocality $locality)
    {
        $locality->delete();

        return response()->json(null, 204);
    }
}
