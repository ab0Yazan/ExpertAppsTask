<?php

namespace Tests\Unit;

use App\Actions\LoginAction;
use App\DataTransferObjects\LoginDto;
use App\DataTransferObjects\LoginResponseDto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;
use Tests\TestCase;

class UserLoginActionTest extends TestCase
{
    use RefreshDatabase;
    public function testUserValidCredentials()
    {
        $data = [
            "name" => "John Doe",
            "email" => "test@mail.com",
            "password" => Hash::make('12345678'),
        ];

        $user= User::factory()->create($data);

        $action= resolve(LoginAction::class);
        $data= $action->execute(LoginDto::fromArray(["email" => $user->email, "password" => "12345678"]));
        $this->assertInstanceOf(LoginResponseDto::class, $data);
        $this->assertObjectHasProperty("token", $data);
    }

    public function testUserInvalidCredentials()
    {
        $data = [
            "name" => "John Doe",
            "email" => "test@mail.com",
            "password" => Hash::make('12345678'),
        ];

        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage("Invalid email or password.");
        $this->expectExceptionCode(401);

        $user= User::factory()->create($data);
        $action= resolve(LoginAction::class);
        $data= $action->execute(LoginDto::fromArray(["email" => $user->email, "password" => "invalid"]));
        $this->assertInstanceOf(LoginResponseDto::class, $data);
        $this->assertObjectHasProperty("token", $data);
    }


}
