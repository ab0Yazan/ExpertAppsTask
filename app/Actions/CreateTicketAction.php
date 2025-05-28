<?php

namespace App\Actions;

use App\DataTransferObjects\UpsertTicketDto;
use App\DataTransferObjects\TicketDetailsDto;
use App\Models\User;
use App\Repositories\Contracts\TicketRepositoryInterface;

class CreateTicketAction
{
    public function __construct(private TicketRepositoryInterface $repo){}

    public function execute(UpsertTicketDto $dto, User $user)
    {
        $ticket= $this->repo->create($dto, $user);

        return TicketDetailsDto::fromModel($ticket);
    }
}
