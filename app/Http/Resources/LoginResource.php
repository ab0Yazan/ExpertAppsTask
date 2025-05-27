<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "token_type" => $this->tokenType,
            "access_token" => $this->token,
            "access_token_expires_in" => $this->personalTokenExpiresIn,
            "refresh_token" => $this->refreshToken,
            "refresh_token_expire_in" => $this->refreshTokenExpiresIn,
            "user" => $this->user,
        ];
    }
}
