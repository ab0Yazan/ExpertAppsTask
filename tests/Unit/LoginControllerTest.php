<?php

namespace Tests\Unit;


use App\Actions\LoginAction;
use App\Http\Controllers\LoginController;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;
    public function testLoginValidCredentials(): void
    {
        $data = [
            "email" => "test@mail.com",
            "password" => "12345678",
        ];

        $user = User::factory()->create($data);

        $request = new LoginRequest($data);
        $action = new LoginAction($request);
        $controller = new LoginController();
        $response = $controller->__invoke($request, $action);
        $data= $response->getData(true);
        $this->assertArrayHasKey('access_token', $data['data']);
        $this->assertArrayHasKey('refresh_token', $data['data']);
    }

    public function testLoginInValidCredentials(): void
    {
        $data = [
            "email" => "test@mail.com",
            "password" => "12345678",
        ];

        $user = User::factory()->create(["password" => Hash::make("invalid")]);

        $request = new LoginRequest($data);
        $action = new LoginAction($request);
        $controller = new LoginController();
        $response = $controller->__invoke($request, $action);
        $data= $response->getData(true);
        $this->assertArrayHasKey('message', $data);
    }
}
