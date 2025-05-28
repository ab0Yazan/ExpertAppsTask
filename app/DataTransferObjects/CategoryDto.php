<?php

namespace App\DataTransferObjects;

use Illuminate\Support\Collection;

readonly final class CategoryDto implements \JsonSerializable
{
    /** @var Collection<int, array{id:int,name:string,children:array}> */
    public readonly Collection $categories;

    public function __construct(Collection $categories)
    {
        $this->categories = $categories->map(fn ($category) => $this->mapCategory($category));
    }

    private function mapCategory($category): array
    {
        return [
            'id'       => $category->id,
            'name'     => $category->name,
            'children' => $category->childrenRecursive
                ->map(fn ($child) => $this->mapCategory($child))
                ->values()
                ->all(),
        ];
    }

    public function toArray(): array
    {
        return $this->categories->values()->all();
    }


    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
