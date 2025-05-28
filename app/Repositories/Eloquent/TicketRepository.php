<?php

namespace App\Repositories\Eloquent;

use App\DataTransferObjects\UpsertTicketDto;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use App\Repositories\Contracts\TicketRepositoryInterface;

class TicketRepository implements TicketRepositoryInterface
{
    public function create(UpsertTicketDto $dto, User $user)
    {
        //create ticket and categories in memory then persist it
        $ticket = new Ticket([
            'name'=> $dto->name,
            'description'=> $dto->description,
            'status'=> $dto->status->value,
            'user_id'=> $user->id,
        ]);
        $dto->categories->map(function (Category $category) use ($ticket) {
            $ticket->categories->push($category);
        });

        $ticket= $ticket->persist();
        return $ticket->load('categories');
    }

    public function update(Ticket $ticket, UpsertTicketDto $dto)
    {
        //create ticket and categories in memory then persist it
        $ticket->fill([
            'name'=> $dto->name,
            'description'=> $dto->description,
            'status'=> $dto->status->value
        ]);
        $dto->categories->map(function (Category $category) use ($ticket) {
            $ticket->categories->push($category);
        });

        $ticket= $ticket->persist();
        return $ticket->load('categories');
    }


    public function filter(array $filters=[])
    {
        return Ticket::select(['tickets.id', 'tickets.name', 'description', 'user_id', 'category_id', 'status'])->with('user', 'categories')
            ->when(isset($filters['name']), function ($q) use ($filters) {
                $q->where(function ($query) use ($filters) {
                    $query->where('tickets.name', 'like', "%{$filters['name']}%")
                        ->orWhereHas('categories', function ($category) use ($filters) {
                            $category->where('name', 'like', "%{$filters['name']}%");
                        });
                });
            })
            ->get();
    }

}
