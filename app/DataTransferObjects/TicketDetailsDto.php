<?php

namespace App\DataTransferObjects;

use App\Enums\TicketStatusEnum;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;

class TicketDetailsDto
{
    public function __construct(
        public int $id,
        public string $name,
        public string $description,
        public Category $category,
        public TicketStatusEnum $status,
        public User $user,
    ) {}

    public static function fromModel(Ticket $ticket): TicketDetailsDto
    {
        return new self(
            $ticket->id,
            $ticket->name,
            $ticket->description,
            $ticket->category,
            $ticket->status,
            $ticket->user,
        );
    }
}
