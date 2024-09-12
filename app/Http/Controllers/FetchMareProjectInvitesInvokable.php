<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\MareInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FetchMareProjectInvitesInvokable extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return MareInvite::where('user_id', Auth::id())->where('status', 0)->with('project')->get();
    }
}
