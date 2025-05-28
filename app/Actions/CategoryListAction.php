<?php

namespace App\Actions;

use App\DataTransferObjects\CategoryDto;
use App\Repositories\Contracts\CategoryRepositoryInterface;

final class CategoryListAction
{
    public function __construct(private CategoryRepositoryInterface $repo){}

    public function execute(array $filters = []): CategoryDto
    {
        return $this->repo->all($filters);
    }
}
