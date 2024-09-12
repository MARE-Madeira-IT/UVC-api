<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\QueryFilters\MareSubstrateFilters;
use App\Http\Resources\MareSubstrateResource;
use App\Models\MareSubstrate;
use Illuminate\Http\Request;

class MareSubstrateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MareSubstrateFilters $filters)
    {
        return MareSubstrateResource::collection(MareSubstrate::filterBy($filters)->paginate(10));
    }

    public function selector()
    {
        return MareSubstrateResource::collection(MareSubstrate::all());
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
     * @param  \App\MareSubstrate  $mareSubstrate
     * @return \Illuminate\Http\Response
     */
    public function show(MareSubstrate $mareSubstrate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MareSubstrate  $mareSubstrate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MareSubstrate $mareSubstrate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MareSubstrate  $mareSubstrate
     * @return \Illuminate\Http\Response
     */
    public function destroy(MareSubstrate $mareSubstrate)
    {
        //
    }
}
