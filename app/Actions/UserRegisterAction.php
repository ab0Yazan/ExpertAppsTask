<?php

namespace App\Actions;

use App\DataTransferObjects\UserRegisterationDto;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

final class UserRegisterAction
{
    public function __construct(private UserRepositoryInterface $repo){}

    public function execute(UserRegisterationDto $dto, string $password) : User
    {
        $password = Hash::make($password);
        return $this->repo->create($dto, $password);
    }
}
