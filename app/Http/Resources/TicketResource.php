<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "relations" => [
                "categories" => $this->categories->map->only(['id', 'name']),
                "user" => ["id" => $this->user->id, "name" => $this->user->name]
            ]
        ];
    }
}
