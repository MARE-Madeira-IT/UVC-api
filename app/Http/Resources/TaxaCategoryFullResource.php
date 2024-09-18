<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaxaCategoryFullResource extends JsonResource
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

        if ($request->survey_program) {
            $taxas = $this->taxas()->where('survey_program_id', $request->survey_program)->get();
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'taxas' => $taxas,
        ];
    }
}
