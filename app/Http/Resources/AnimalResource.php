<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnimalResource extends JsonResource
{
    /**
     * Transforme o recurso num array.
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'species'    => $this->species,
            'breed'      => $this->breed,
            'age'        => $this->age,
            'size'       => $this->size,
            'status'     => $this->status,
            // Adicionamos estas datas apenas se forem úteis para o adotante
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d') : null,
        ];
    }
}