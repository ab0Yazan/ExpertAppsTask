<?php

namespace App\Http\Requests;

use App\DataTransferObjects\LoginDto;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "email" => "required|email",
            "password" => "required|min:8"
        ];
    }

    public function getLoginDto(): LoginDto
    {
        return LoginDto::fromArray($this->all());
    }
}
