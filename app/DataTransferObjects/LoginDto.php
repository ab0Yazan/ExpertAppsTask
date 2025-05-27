<?php

namespace App\DataTransferObjects;

final class LoginDto
{
    protected string $email;
    protected string $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public static function fromArray(array $data): LoginDto
    {
        return new self($data['email'], $data['password']);
    }

}
