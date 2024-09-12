<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MareMotileGroupedResource extends JsonResource
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
            'report_id' => $this->report_id,
            'code' => $this->report["code"],
            'type' => $this->type,
            'children' => MareMotileResource::collection($this->motiles),
        ];
    }
}
