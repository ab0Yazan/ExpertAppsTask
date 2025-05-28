<?php

namespace App\Actions;

use App\DataTransferObjects\TicketDetailsDto;
use App\DataTransferObjects\UpsertTicketDto;
use App\Models\Ticket;

class UpdateTicketAction
{
    public function execute(UpsertTicketDto $dto,Ticket $ticket)
    {
        #repo
        $ticket->update([
            'category_id' => $dto->category?->id,
            'name' => $dto->name,
            'description' => $dto->description,
            'status' => $dto->status?->value,
        ]);

        return TicketDetailsDto::fromModel($ticket->fresh());
    }
}
