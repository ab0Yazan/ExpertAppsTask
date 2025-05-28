<?php

namespace App\Http\Requests;

use App\DataTransferObjects\UpsertTicketDto;
use App\Enums\TicketStatusEnum;
use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;

class UpsertTicketRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'status' => ['required', 'boolean'],
            'category_ids' => ['required', 'array'],
            'category_ids.*' => ['required', 'exists:categories,id'],
        ];
    }

    public function getTicketDto(): UpsertTicketDto
    {
        return UpsertTicketDto::fromArray(
            [
                'status' => $this->getStatus(),
                'categories' => $this->getCategories()] +
            $this->all());
    }


    private function getStatus(): TicketStatusEnum
    {
        return TicketStatusEnum::from($this->status);
    }

    private function getCategories(): Collection
    {
        return resolve(CategoryRepositoryInterface::class)->getByIds($this->input('category_ids'));
    }
}
