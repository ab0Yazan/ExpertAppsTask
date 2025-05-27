<?php

namespace App\DataTransferObjects;


final readonly class UserRegisterationDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
    ) {
        self::assertValidEmail($email);
    }

    public static function fromArray(array $data): self
    {
        self::assertValidEmail($data['email']);

        return new self(
            name: $data['name'],
            email: $data['email']
        );
    }

    private static function assertValidEmail(string $email): void
    {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email address: {$email}");
        }
    }
}
