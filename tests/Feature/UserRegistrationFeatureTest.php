<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserRegistrationFeatureTest extends TestCase
{
    use RefreshDatabase;
    public function testRegistrationValidCredentials(): void
    {
        $password = fake()->password(8);
        $data = [
            "name" => fake()->name,
            "email" => fake()->email,
            "password" => $password,
            "password_confirmation" => $password
        ];

        $response= $this->post("api/v1/auth/register", $data, ['Accept' => 'application/json']);
        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('users', ["name"=> $data["name"], "email"=> $data["email"]]);
    }

    public function testRegistrationInValidCredentials(): void
    {
        $password = fake()->password(8);
        $data = [
            "name" => fake()->name,
            "email" => "invalid-email",
            "password" => $password,
            "password_confirmation" => "invalid"
        ];

        $response= $this->post("api/v1/auth/register", $data, ['Accept' => 'application/json']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


}
