<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BenthicResource extends JsonResource
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
            'p' => $this["p##"],
            'notes' => $this->notes,
            'substrate_id' => $this->substrate_id,
            'taxa_id' => $this->taxa_id,
            'substrate' => $this->substrate,
            'taxa' => new TaxaResource($this->taxa),
            'notes' => $this->notes,
            'code' => $this->report->getCode(),
        ];
    }
}
