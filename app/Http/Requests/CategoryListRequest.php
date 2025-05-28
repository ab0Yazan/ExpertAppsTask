<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'nullable|string',
            'id' => 'nullable|int'
        ];
    }

    public function getFilters(): array
    {
        if($this->get('name', null) || $this->get('id', null)){
            return [
                'name' => $this->get('name'),
                'id' => $this->get('id'),
            ];
        }
        return [];
    }
}
