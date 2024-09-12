<?php

namespace App\Http\Resources;

use App\Models\MareProjectHasUser;
use Illuminate\Http\Resources\Json\JsonResource;

class MareUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return  [
            'id' => $this->user->id,
            'project_id' => $this->project_id,
            'email' => $this->user->email,
            'note' => $this->user->note,
            'active' => $this->user->active,
            'admin' => $this->user->hasRole('admin') ? 1 : 0,
            'note' => $this->user->note,
            'photo' => $this->user->photo,
            'is_verified' => $this->user->is_verified,
            'occupation' => $this->user->occupation,
            'userable' => [
                'type_name' => $this->user->userable_type,
                'certificates' => $this->user->certificates,
                'user' => new UserPersonResource($this->user->userPerson),
            ],
            'permissions' => $this->permissions,
        ];
    }
}
