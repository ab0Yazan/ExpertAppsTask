<?php

namespace App\Actions;

use App\DataTransferObjects\UserRegisterationDto;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

final class UserRegisterAction
{
    public function execute(UserRegisterationDto $dto, string $password) : User
    {
        $user = User::create([
            'name'=>$dto->name,
            'email'=>$dto->email,
            'password'=>Hash::make($password),
        ]);

        return $user;
    }
}
