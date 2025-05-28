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

class TicketController extends Controller
{
    public function store(UpsertTicketRequest $request, CreateTicketAction $action)
    {
        if(!auth()->check())
            return self::apiResponse()->error("user not authenticated", Response::HTTP_UNAUTHORIZED);

        $ticket= $action->execute($request->getTicketDto(), auth()->user());
        return self::apiResponse()->created(TicketResource::make($ticket));
    }

    public function update(UpsertTicketRequest $request, UpdateTicketAction $action, Ticket $ticket)
    {
        if(!auth()->check())
            return self::apiResponse()->error("user not authenticated", Response::HTTP_UNAUTHORIZED);

        $ticket= $action->execute($request->getTicketDto(), $ticket);
        return self::apiResponse()->ok(TicketResource::make($ticket));
    }

    public function filter(ListTicketRequest $request, ListTicketAction $action)
    {
        if(!auth()->check())
            return self::apiResponse()->error("user not authenticated", Response::HTTP_UNAUTHORIZED);

        $ticket= $action->execute($request->getTicketFilterDto());
        return self::apiResponse()->ok(TicketResource::collection($ticket));
    }
}
