<?php

namespace App\Repositories\Contracts;

use App\DataTransferObjects\UserRegisterationDto;

interface UserRepositoryInterface
{
    public function getById($userId);
    public function findBy(array $criteria, array $columns = ['*'], $with=[]);
    public function create(UserRegisterationDto $dto, string $password);
}
