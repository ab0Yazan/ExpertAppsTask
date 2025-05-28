<?php

namespace App\DataTransferObjects;

use App\Enums\TicketStatusEnum;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

final readonly class UpsertTicketDto
{
    public function __construct(
        public string $name,
        public string $description,
        public TicketStatusEnum $status,
        public Collection $categories,
    ) {}

    public static function fromArray(array $data): UpsertTicketDto
    {
        return new self(
            name: $data['name'],
            description: $data['description']??null,
            status: $data['status'],
            categories: $data['categories']
        );
    }
}
