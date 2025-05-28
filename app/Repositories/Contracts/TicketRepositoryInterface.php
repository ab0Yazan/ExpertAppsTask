<?php

namespace App\Repositories\Contracts;

use App\DataTransferObjects\UpsertTicketDto;
use App\Models\Ticket;
use App\Models\User;

interface TicketRepositoryInterface
{
    public function create(UpsertTicketDto $dto, User $user);
    public function update(Ticket $ticket, UpsertTicketDto $dto);
}
