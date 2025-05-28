<?php

namespace App\DataTransferObjects;

final class TicketFilterDto
{
    public function __construct(public string|null $name){}
}
