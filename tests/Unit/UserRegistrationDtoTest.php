<?php

namespace Tests\Unit;

use App\DataTransferObjects\UserRegisterationDto;
use InvalidArgumentException;
use Tests\TestCase;

class UserRegistrationDtoTest extends TestCase
{
    public function testCanBeInstantiatedWithValidData(): void
    {
        $name = fake()->name;
        $email = fake()->email;

        $dto = new UserRegisterationDto(name: $name, email: $email);

        $this->assertInstanceOf(UserRegisterationDto::class, $dto);
        $this->assertEquals($name, $dto->name);
        $this->assertEquals($email, $dto->email);
    }

    public function testCanBeInstantiatedWithArrayValidData(): void
    {
        $data = [
            'name' => fake()->name,
            'email' => fake()->email,
        ];

        $dto = UserRegisterationDto::fromArray($data);

        $this->assertInstanceOf(UserRegisterationDto::class, $dto);
        $this->assertEquals($data['name'], $dto->name);
        $this->assertEquals($data['email'], $dto->email);
    }

    public function testThrowsExceptionForInvalidEmailInConstructor(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid email address: invalid-email");

        new UserRegisterationDto(name: fake()->name, email: 'invalid-email');
    }

    public function testThrowsExceptionForInvalidEmailInFromArray(): void
    {
        $data = [
            'name' => fake()->name,
            'email' => "invalid-email",
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid email address: invalid-email");

        UserRegisterationDto::fromArray($data);
    }
}
