<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class SurveyProgramResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'users' => SurveyProgramUserResource::collection($this->surveyProgramUsers),
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'permissions' => $this->surveyProgramUsers()->where('user_id', Auth::id())->first()->permissions->pluck("name"),

        ];
    }
}
