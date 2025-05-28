<?php

namespace App\Actions;

use App\DataTransferObjects\TicketDetailsDto;
use App\DataTransferObjects\UpsertTicketDto;
use App\Models\Ticket;
use App\Repositories\Contracts\TicketRepositoryInterface;

class UpdateTicketAction
{
    public function __construct(private TicketRepositoryInterface $repo){}

    public function execute(UpsertTicketDto $dto,Ticket $ticket)
    {
        $this->repo->update($ticket, $dto);

        return TicketDetailsDto::fromModel($ticket->fresh());
    }
}
