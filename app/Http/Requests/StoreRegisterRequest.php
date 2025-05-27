<?php

namespace App\Http\Requests;

use App\DataTransferObjects\UserRegisterationDto;
use Illuminate\Foundation\Http\FormRequest;

class StoreRegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "name"=>"required|string",
            "email"=>"required|email",
            "password"=>"required|string|min:8",
            "password_confirmation"=>"required|string|same:password",
        ];
    }

    public function getRegisterUserDto(): UserRegisterationDto
    {
        return UserRegisterationDto::fromArray($this->all());
    }
}
