<?php

namespace App\Repositories\Eloquent;

use App\DataTransferObjects\CategoryDto;
use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class CategoryRepository implements CategoryRepositoryInterface
{

    public function all(array $filters=[])
    {
        if(empty($filters)){
            $categories = Cache::remember('category_tree', now()->addDay(), function () {
                return $this->baseQuery()->get();
            });
        }
        else{
            $categories = $this->applyFilters($this->baseQuery(), $filters)->get();
        }
        return new CategoryDto($categories);
    }

    private function baseQuery()
    {
        return Category::with('childrenRecursive')->whereNull('parent_id');
    }

    private function applyFilters($query, array $filters)
    {
        return $query
            ->when($filters['name'] ?? null, fn ($q, $name) => $q->where('name', 'like', "%$name%"))
            ->when($filters['id'] ?? null, fn ($q, $id) => $q->where('id', $id));
    }

    public function getById(int $id)
    {
       return Category::findOrFail($id);
    }

    public function getByIds(array $ids)
    {
        return Category::whereIn('id', $ids)->get();
    }
}
