<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Invite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FetchProjectInvitesInvokable extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return Invite::where('user_id', Auth::id())->where('status', 0)->with('project')->get();
    }
}
