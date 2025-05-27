<?php

namespace Tests\Unit;


use App\DataTransferObjects\LoginResponseDto;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class LoginResponseDtoTest extends TestCase
{
    public function testCanBeInstantiatedWithCorrectProperties()
    {
        $token = 'token';
        $tokenType = 'Bearer';
        $personalTokenExpiresIn = Carbon::now()->addHours(1);
        $refreshTokenExpiresIn = Carbon::now()->addDays(7);
        $refreshToken = 'tiken';
        $user = User::factory()->make(['id' => 1, 'name' => 'Test User']);

        $dto = new LoginResponseDto(
            token: $token,
            tokenType: $tokenType,
            personalTokenExpiresIn: $personalTokenExpiresIn,
            refreshTokenExpiresIn: $refreshTokenExpiresIn,
            refreshToken: $refreshToken,
            user: $user
        );

        $this->assertInstanceOf(LoginResponseDto::class, $dto);
        $this->assertEquals($token, $dto->token);
        $this->assertEquals($tokenType, $dto->tokenType);
        $this->assertEquals($personalTokenExpiresIn, $dto->personalTokenExpiresIn);
        $this->assertInstanceOf(Carbon::class, $dto->personalTokenExpiresIn);
        $this->assertEquals($refreshTokenExpiresIn, $dto->refreshTokenExpiresIn);
        $this->assertInstanceOf(Carbon::class, $dto->refreshTokenExpiresIn);
        $this->assertEquals($refreshToken, $dto->refreshToken);
        $this->assertEquals($user, $dto->user);
        $this->assertInstanceOf(User::class, $dto->user);
    }
}
