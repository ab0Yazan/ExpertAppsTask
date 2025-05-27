<?php

namespace App\DataTransferObjects;

use App\Models\User;
use Carbon\Carbon;

final readonly class LoginResponseDto
{
    public function __construct(
        public string $token,
        public string $tokenType,
        public Carbon $personalTokenExpiresIn,
        public Carbon $refreshTokenExpiresIn,
        public string $refreshToken,
        public User   $user
    ) {}
}
