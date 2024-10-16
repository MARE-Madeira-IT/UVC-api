<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'state' => $this->state,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'created_at' => $this->created_at,
            'reports' => $this->reports()->limit(10)->get()->pluck('code'),
            'depths' => $this->depths()->limit(10)->get()->pluck('name'),
            'localities' => $this->localities()->limit(10)->get()->pluck('name'),
            'sites' => $this->sites()->limit(10)->get()->pluck('name'),
            'taxas' => $this->taxas()->limit(10)->get()->pluck('name'),
            'taxa_categories' => $this->taxaCategories()->limit(10)->get()->pluck('name'),
        ];
    }
}
