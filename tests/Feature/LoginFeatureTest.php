<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginFeatureTest extends TestCase
{

    use RefreshDatabase;
    public function testLoginValidCredentials(): void
    {
        $password = 'password';
        $user= User::factory()->create(["password" => Hash::make($password)]);
        $response= $this->post("api/v1/auth/login", ["email" => $user->email, "password"=>"password"], ['Accept' => 'application/json']);
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testLoginInValidCredentials(): void
    {
        $user= User::factory()->create();
        $response= $this->post("api/v1/auth/login", ["email" => $user->email, "password"=>"invalid password"], ['Accept' => 'application/json']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
