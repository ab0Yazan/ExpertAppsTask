<?php

namespace App\Http\Controllers;

use App\Actions\CreateTicketAction;
use App\Actions\ListTicketAction;
use App\Actions\UpdateTicketAction;
use App\Http\Requests\ListTicketRequest;
use App\Http\Requests\UpsertTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\Response;
use Illuminate\Validation\UnauthorizedException;

class TicketController extends Controller
{
    public function __construct()
    {
        if(!auth()->check())
            throw new UnauthorizedException("user not authenticated");

    }
    public function store(UpsertTicketRequest $request, CreateTicketAction $action)
    {
        $ticket= $action->execute($request->getTicketDto(), auth()->user());
        return self::apiResponse()->created(TicketResource::make($ticket));
    }

    public function update(UpsertTicketRequest $request, UpdateTicketAction $action, Ticket $ticket)
    {
        $ticket= $action->execute($request->getTicketDto(), $ticket);
        return self::apiResponse()->ok(TicketResource::make($ticket));
    }

    public function filter(ListTicketRequest $request, ListTicketAction $action)
    {
        $ticket= $action->execute($request->getTicketFilterDto());
        return self::apiResponse()->ok(TicketResource::collection($ticket));
    }
}
