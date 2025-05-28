<?php

namespace Tests\Feature;

use App\Models\Category;
use App\TicketStatusEnum;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class TicketCreateFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CategorySeeder::class);
    }

    public function testStoreValidData(): void
    {
        $user= \App\Models\User::factory()->create();
        $this->actingAs($user);

        $data = [
            "name" => fake()->title,
            "description" => fake()->title,
            "category_id" => Category::inRandomOrder()->first()->id,
            "status" => TicketStatusEnum::OPENED->value,
            "user" => $user,
        ];

        $response= $this->post("api/v1/tickets", $data, ['Accept' => 'application/json']);
        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('tickets', ["name"=> $data["name"]]);
    }

    public function testStoreInValidData(): void
    {
        $user= \App\Models\User::factory()->create();
        $this->actingAs($user);

        $data = [
            "name" => fake()->title,
            "description" => fake()->title,
            "category_id" => 99999,
            "status" => TicketStatusEnum::OPENED->value
        ];

        $response= $this->post("api/v1/tickets", $data, ['Accept' => 'application/json']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertDatabaseMissing('tickets', ["name"=> $data["name"]]);
    }
}
