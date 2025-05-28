<?php

namespace App\Actions;

use App\DataTransferObjects\LoginDto;
use App\DataTransferObjects\LoginResponseDto;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;

class LoginAction
{
    public function __construct(private UserRepositoryInterface $repo){}
    public function execute(LoginDto $dto) : LoginResponseDto
    {
        $user= $this->validateCredentials($dto->getEmail(), $dto->getPassword());
        $p_token = $user->createToken('personal_access_token', ['*'], now()->addHour())->plainTextToken;
        $r_token = $user->createToken('refresh_token', ['refresh'], now()->addDay())->plainTextToken;

        return new LoginResponseDto(
            token: $p_token,
            tokenType: 'bearer',
            personalTokenExpiresIn: now()->addHour(),
            refreshTokenExpiresIn: now()->addDay(),
            refreshToken: $r_token,
            user: $user
        );
    }

    private function validateCredentials(string $email, string $password)
    {
        $user = $this->repo->findBy(['email' => $email])->first();
        if (! $user || ! Hash::check($password, $user->password)) {
            throw new UnauthorizedException('Invalid email or password.', Response::HTTP_UNAUTHORIZED);
        }

        return $user;
    }
}
