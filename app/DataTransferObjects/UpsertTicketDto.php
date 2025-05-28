<?php

namespace App\DataTransferObjects;

use App\Enums\TicketStatusEnum;
use App\Models\Category;

final readonly class UpsertTicketDto
{
    public function __construct(
        public string $name,
        public string $description,
        public Category $category,
        public TicketStatusEnum $status
    ) {}

    public static function fromArray(array $data): UpsertTicketDto
    {
        return new self(
            name: $data['name'],
            description: $data['description']??null,
            category: $data['category'],
            status: $data['status'],
        );
    }
}
