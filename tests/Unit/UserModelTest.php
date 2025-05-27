<?php

namespace Tests\Unit;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function testCanCreateUser()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $user = User::factory()->create($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
        $this->assertEquals($userData['name'], $user->name);
        $this->assertEquals($userData['email'], $user->email);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function testUserAttributesAreFillable()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ];

        $user = User::create($userData);

        $this->assertEquals($userData['name'], $user->name);
        $this->assertEquals($userData['email'], $user->email);
        $this->assertEquals($userData['password'], $user->password);
    }

    public function testPasswordAttributeIsHidden()
    {
        $user = User::factory()->create(['password' => 'password123']);
        $userArray = $user->toArray();

        $this->assertArrayNotHasKey('password', $userArray);
    }

    public function testRemeberTokenIsHidden()
    {
        $user = User::factory()->create();
        $user->remember_token = 'token';
        $user->save();

        $userArray = $user->toArray();
        $this->assertArrayNotHasKey('remember_token', $userArray);
    }

    public function testVerfiedAtCastedToDatetime()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $this->assertInstanceOf(Carbon::class, $user->email_verified_at);
    }
}
