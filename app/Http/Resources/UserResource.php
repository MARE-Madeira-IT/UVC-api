<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)

    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'active' => $this->active,
            'note' => $this->note,
            'photo' => $this->photo,
            'is_verified' => $this->is_verified,
            'occupation' => $this->occupation,
            'roles' =>  $this->when($this->roles->count() !== 0, $this->roles),
            'userable' => [
                'type_name' => $this->userable_type,
                'certificates' => $this->certificates,
                'user' => new UserPersonResource($this->userPerson),
            ],
        ];
    }
}
