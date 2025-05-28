<?php

namespace Tests\Unit;

use App\Actions\UserRegisterAction;
use App\Http\Controllers\RegisterController;
use App\Http\Requests\StoreRegisterRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;
    public function testRegisterControllerValidEmail()
    {
        $data = [
            "name" => fake()->name,
            "password" => "password",
            "password_confirmation" => "password",
            "email" => fake()->email,
        ];
        $request= new StoreRegisterRequest($data);
        $controller = new RegisterController();
        $action= resolve(UserRegisterAction::class);
        $response= $controller->__invoke($request, $action);
        $json= $response->getData(true);
        $this->assertArrayHasKey("email", $json["data"]);
        $this->assertDatabaseHas("users", ["name" => $data["name"], "email" => $data["email"]]);
    }

    public function testRegisterControllerInValidEmail()
    {
        $data = [
            "name" => fake()->name,
            "password" => "password",
            "password_confirmation" => "password",
            "email" => "invalid-email",
        ];

        $request= new StoreRegisterRequest($data);
        $controller = new RegisterController();
        $action= resolve(UserRegisterAction::class);
        $response = $controller->__invoke($request, $action);
        $json= $response->getData(true);
        $this->assertEquals("error", $json["status"]);
    }
}
