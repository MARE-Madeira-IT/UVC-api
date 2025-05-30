<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
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
            'date' => $this->date,
            'code' => $this->getCode(),
            'site' => $this->site,
            'transect' => $this->transect,
            'time' => $this->time,
            'dom_substrate' => $this->dom_substrate,
            'daily_dive' => $this->daily_dive,
            'replica' => $this->replica,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'heading' => $this->heading,
            'heading_direction' => $this->heading_direction,
            'site_area' => $this->site_area,
            'distance' => $this->distance,
            'depth' => $this->depth,
            'functions' => $this->functions,
            'surveyed_area' => $this->surveyed_area,

            'site' => new SiteResource($this->site),
            'created_at' => (string) $this->created_at,
        ];
    }
}
