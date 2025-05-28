<?php

namespace Tests\Feature;

use App\Enums\TicketStatusEnum;
use App\Models\Category;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class UpdateTicketFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CategorySeeder::class);
    }

    public function testUpdateValidData(): void
    {
        $user= \App\Models\User::factory()->create();
        $this->actingAs($user);

        $ticket= \App\Models\Ticket::factory()->create();

        $data = [
            "name" => fake()->title,
            "description" => fake()->title,
            "category_id" => Category::inRandomOrder()->first()->id,
            "status" => TicketStatusEnum::OPENED->value,
            "user" => $user,
        ];

        $response= $this->put("api/v1/ticket/{$ticket->id}", $data, ['Accept' => 'application/json']);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('tickets', ["name"=> $data["name"]]);
    }

    public function testUpdateInValidData(): void
    {
        $user= \App\Models\User::factory()->create();
        $this->actingAs($user);

        $ticket= \App\Models\Ticket::factory()->create();

        $data = [
            "name" => fake()->title,
            "description" => fake()->title,
            "category_id" => 99999,
            "status" => TicketStatusEnum::OPENED->value
        ];

        $response= $this->put("api/v1/ticket/{$ticket->id}", $data, ['Accept' => 'application/json']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertDatabaseMissing('tickets', ["name"=> $data["name"]]);
    }
}
