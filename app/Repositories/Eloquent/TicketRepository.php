<?php

namespace App\Repositories\Eloquent;

use App\DataTransferObjects\UpsertTicketDto;
use App\Models\Ticket;
use App\Models\User;
use App\Repositories\Contracts\TicketRepositoryInterface;

class TicketRepository implements TicketRepositoryInterface
{

    public function create(UpsertTicketDto $dto, User $user)
    {
        return Ticket::create([
            'name'=> $dto->name,
            'description'=> $dto->description,
            'category_id'=> $dto->category->id,
            'status'=> $dto->status->value,
            'user_id'=> $user->id,
        ]);
    }

    public function update(Ticket $ticket, UpsertTicketDto $dto)
    {
        return $ticket->update([
            'category_id' => $dto->category->id,
            'name' => $dto->name,
            'description' => $dto->description,
            'status' => $dto->status?->value,
        ]);
    }

    public function filter(array $filters=[])
    {
        return Ticket::select(['tickets.id', 'tickets.name', 'description', 'user_id', 'category_id', 'status'])->with('user', 'category')
            ->when(isset($filters['name']), function ($q) use ($filters) {
                $q->join('categories', 'tickets.category_id', '=', 'categories.id')
                    ->where('tickets.name', 'like', "%{$filters['name']}%")
                    ->orWhere('categories.name', 'like', "%{$filters['name']}%");
            })
            ->get();
    }
}
