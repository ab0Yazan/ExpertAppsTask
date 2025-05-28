<?php

namespace App\Actions;

use App\DataTransferObjects\UpsertTicketDto;
use App\DataTransferObjects\TicketDetailsDto;
use App\Models\Ticket;
use App\Models\User;

class CreateTicketAction
{
    public function execute(UpsertTicketDto $dto, User $user)
    {
        $ticket= Ticket::create([
            'name'=> $dto->name,
            'description'=> $dto->description,
            'category_id'=> $dto->category->id,
            'status'=> $dto->status->value,
            'user_id'=> $user->id,
        ]);

        return TicketDetailsDto::fromModel($ticket);
    }
}
