<?php

namespace App\Repositories\Eloquent;

use App\DataTransferObjects\UserRegisterationDto;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function getById($userId)
    {
        return User::findOrFail($userId);
    }

    public function findBy(array $criteria, array $columns = ['*'], $with=[])
    {
        return User::where($criteria)->when($with, function ($query) use ($with){
            $query->with($with);
        })->select($columns)->get();
    }

    public function create(UserRegisterationDto $dto, string $password){
        return User::create([
            'name'=>$dto->name,
            'email'=>$dto->email,
            'password'=>$password,
        ]);
    }
}
