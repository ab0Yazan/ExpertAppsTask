<?php

namespace App\Actions;

use App\DataTransferObjects\CategoryDto;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

final class CategoryListAction
{
    public function execute(array $filters = []): CategoryDto
    {
        if (empty($filters)) {
            $categories = Cache::remember('category_tree', now()->addDay(), function () {
                return $this->baseQuery()->get();
            });
        } else {
            $categories = $this->applyFilters($this->baseQuery(), $filters)->get();
        }

        return new CategoryDto($categories);
    }

    protected function baseQuery()
    {
        return Category::with('childrenRecursive')->whereNull('parent_id');
    }

    protected function applyFilters($query, array $filters)
    {
        return $query
            ->when($filters['name'] ?? null, fn ($q, $name) => $q->where('name', 'like', "%$name%"))
            ->when($filters['id'] ?? null, fn ($q, $id) => $q->where('id', $id));
    }

}
