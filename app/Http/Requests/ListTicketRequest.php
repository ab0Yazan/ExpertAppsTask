<?php

namespace App\Http\Requests;

use App\DataTransferObjects\TicketFilterDto;
use Illuminate\Foundation\Http\FormRequest;

class ListTicketRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'nullable|string',
        ];
    }

    public function getTicketFilterDto(): TicketFilterDto
    {
        return new TicketFilterDto($this->input('name'));
    }
}
