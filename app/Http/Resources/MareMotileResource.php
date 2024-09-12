<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MareMotileResource extends JsonResource
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
            'size' => $this->size,
            'ntotal' => $this->ntotal,
            'notes' => $this->notes,
            'density/1' => $this["density/1"] ?? 'N/A',
            'biomass/1' => $this["biomass/1"] ?? 'N/A',
            'report' => $this->report,
            'sizeCategory' => $this->sizeCategory,
            'taxa' => new MareTaxaResource($this->taxa),
            'code' => $this->mareReportMotile->report["code"],
            'type' => $this->mareReportMotile->type,
        ];
    }
}
