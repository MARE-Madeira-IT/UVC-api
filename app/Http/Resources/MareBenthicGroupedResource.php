<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MareBenthicGroupedResource extends JsonResource
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
            'report_id' => $this->id,
            'code' => $this->code,
            'children' => MareBenthicResource::collection($this->benthics),
        ];
    }
}
