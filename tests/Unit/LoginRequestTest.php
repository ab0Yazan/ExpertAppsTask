<?php

namespace Tests\Unit;


use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class LoginRequestTest extends TestCase
{
    public function testRulesMethodHasValidAttributes(): void
    {
        $request = new LoginRequest();
        $data = $request->rules();
        $this->assertIsArray($data);
        $this->assertArrayHasKey('email', $data);
        $this->assertArrayHasKey('password', $data);
    }

    public function testLoginRequestValidation()
    {
        $request = new LoginRequest();
        $request->merge(['email' => fake()->email, "password" => fake()->password, "name" => fake()->name]);
        $validator = Validator::make($request->all(), $request->rules());
        $this->assertTrue($validator->passes());
    }

    public function testLoginRequestValidationForInvalidEmail()
    {
        $request = new LoginRequest();
        $request->merge(['email' => "invalid email", "password" => fake()->password, "name" => fake()->name]);
        $validator = Validator::make($request->all(), $request->rules());
        $this->assertTrue($validator->fails());
        $this->assertEquals("The email field must be a valid email address.",$validator->errors()->first('email'));
    }
}
