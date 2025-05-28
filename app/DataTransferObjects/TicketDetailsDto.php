<?php

namespace App\DataTransferObjects;

use App\Enums\TicketStatusEnum;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class TicketDetailsDto
{
    public function __construct(
        public int $id,
        public string $name,
        public string $description,
        public Collection $categories,
        public TicketStatusEnum $status,
        public User $user,
    ) {}

    public static function fromModel(Ticket $ticket): TicketDetailsDto
    {
        return new self(
            $ticket->id,
            $ticket->name,
            $ticket->description,
            $ticket->categories,
            $ticket->status,
            $ticket->user
        );
    }
}
