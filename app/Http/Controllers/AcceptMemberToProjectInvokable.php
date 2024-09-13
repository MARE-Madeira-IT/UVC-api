<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invite;
use App\Models\ProjectHasUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcceptMemberToProjectInvokable extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Invite $invite, Request $request)
    {
        $invite->status = $request->status;
        $invite->save();

        if ($request->status == 1) {
            $mareProjectHasUser = ProjectHasUser::where('project_id', $invite->project_id)->where('user_id', $invite->user_id)->first();
            if ($mareProjectHasUser) {
                $mareProjectHasUser->update([
                    'active' => true
                ]);
            }
        }

        return Invite::where('user_id', Auth::id())->where('status', 0)->with('project')->get();
    }
}
