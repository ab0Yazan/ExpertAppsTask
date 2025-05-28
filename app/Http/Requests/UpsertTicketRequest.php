<?php

namespace App\Http\Requests;

use App\DataTransferObjects\UpsertTicketDto;
use App\Enums\TicketStatusEnum;
use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class UpsertTicketRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'status' => ['required', 'boolean'],
        ];
    }

    public function getTicketDto(): UpsertTicketDto
    {
        return UpsertTicketDto::fromArray(
            ['category' => $this->getCategory(),
                'status' => $this->getStatus(), ] +
            $this->all());
    }

    private function getCategory(): Category
    {
        #repo
        return Category::find($this->category_id);
    }

    private function getStatus(): TicketStatusEnum
    {
        return TicketStatusEnum::from($this->status);
    }
}
