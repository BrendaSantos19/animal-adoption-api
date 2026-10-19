<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'status'           => $this->status,
            'rejection_reason' => $this->rejection_reason,
            'animal'           => [
                'id'    => $this->animal->id,
                'breed' => $this->animal->breed,
            ],
            'adopter_id'       => $this->adopter_id,
            'created_at'       => $this->created_at,
        ];
    }
}
