<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SurveyProgramUserResource extends JsonResource
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
            'email' => $this->user->email,
            'active' => $this->user->active,
            'note' => $this->user->note,
            'photo' => $this->user->photo,
            'is_verified' => $this->user->is_verified,
            'accepted' => $this->accepted,
            'occupation' => $this->user->occupation,
            'userable' => [
                'type_name' => $this->user->userable_type,
                'certificates' => $this->user->certificates,
                'user' => new UserPersonResource($this->user->userPerson),
            ],
            'surveyProgram' => [
                "name" => $this->surveyProgram->name
            ],
            'permissions' => $this->permissions
        ];
    }
}
