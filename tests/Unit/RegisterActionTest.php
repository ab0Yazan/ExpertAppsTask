<?php

namespace Tests\Unit;

use App\Actions\UserRegisterAction;
use App\DataTransferObjects\UserRegisterationDto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterActionTest extends TestCase
{
    use RefreshDatabase;

    public function testRegisterUserAction(): void
    {
        $data = [
            "name" => fake()->name,
            "email" => fake()->email,
            "password" => "12345678",
            "password_confirmation" => "12345678"
        ];

        $dto=  UserRegisterationDto::fromArray($data);
        $action = resolve(UserRegisterAction::class);
        $user = $action->execute($dto, $data['password']);
        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', $user->getAttributes());
    }

    public function testRegisterUserActionInvalidEmail(): void
    {
        $data = [
            "name" => fake()->name,
            "email" => "invalid-email",
            "password" => "12345678",
            "password_confirmation" => "12345678"
        ];

        $this->expectException(\InvalidArgumentException::class);

        $dto=  UserRegisterationDto::fromArray($data);
        $action = resolve(UserRegisterAction::class);
        $action->execute($dto, $data['password']);
    }
}
