<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class WorkspaceResource extends JsonResource
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
            'users' => WorkspaceUserResource::collection($this->workspaceUsers),
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'permissions' => $this->workspaceUsers()->where('user_id', Auth::id())->first()->permissions->pluck("name"),
        ];
    }
}
