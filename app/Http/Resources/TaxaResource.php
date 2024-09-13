<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaxaResource extends JsonResource
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
            'photo_url' => $this->photo_url ? config('app.url') . '/' . $this->photo_url : null, //TODO: verify APP_URL in prod env
            'validated' => $this->validated,
            'name' => $this->name,
            'genus' => $this->genus,
            'species' => $this->species,
            'phylum' => $this->phylum,
            'category' => $this->category,
            'indicators' => $this->indicators,
        ];
    }
}
