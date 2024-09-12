<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MareTaxaCategoryFullResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $taxas = $this->taxas;

        if ($request->project) {
            $taxas = $this->taxas()->where('project_id', $request->project)->get();
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'taxas' => $taxas,
        ];
    }
}
