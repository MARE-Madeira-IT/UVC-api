<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProjectResource extends JsonResource
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
            'workspace_id' => $this->workspace_id,
            'name' => $this->name,
            'description' => $this->description,
            'geographic_area' => $this->geographic_area,
            'start_period' => $this->start_period,
            'end_period' => $this->end_period,
            'stage' => $this->stage,
            'contact' => $this->contact,
            'public' => $this->public,
            'community_size' => $this->community_size,
            'users' => ProjectUserResource::collection($this->projectUsers),
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'permissions' => $this->projectUsers()?->where('user_id', Auth::id())?->first()?->permissions?->pluck("name"),

        ];
    }
}
