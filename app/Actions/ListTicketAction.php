<?php

namespace App\Actions;

use App\DataTransferObjects\TicketDetailsDto;
use App\DataTransferObjects\TicketFilterDto;
use App\Repositories\Contracts\TicketRepositoryInterface;

class ListTicketAction
{
    public function __construct(private TicketRepositoryInterface $repo){}
    public function execute(TicketFilterDto $dto){
        $filters = $dto->name?['name' => $dto->name]:[];
        $tickets= $this->repo->filter($filters);
        return $tickets->map(function($ticket){
            return TicketDetailsDto::fromModel($ticket);
        });
    }
}
