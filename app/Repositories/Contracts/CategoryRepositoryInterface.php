<?php

namespace App\Repositories\Contracts;

interface CategoryRepositoryInterface
{
    public function all(array $filters);
    public function getById(int $id);
    public function getByIds(array $ids);

}
