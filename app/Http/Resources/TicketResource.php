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
                "category" => ["id" => $this->category->id, "name" => $this->category->name],
                "user" => ["id" => $this->user->id, "name" => $this->user->name]
            ]
        ];
    }
}
