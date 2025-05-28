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

        $categories = Category::inRandomOrder()->limit(3)->get();

        $data = [
            "name" => fake()->title,
            "description" => fake()->title,
            "category_ids" => $categories->pluck('id')->toArray(),
            "status" => TicketStatusEnum::OPENED->value,
            "user" => $user,
        ];

        $response= $this->put("api/v1/ticket/edit/{$ticket->id}", $data, ['Accept' => 'application/json']);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('tickets', ["name"=> $data["name"]]);
        $categories->each(function ($category) use ($ticket){
            $this->assertDatabaseHas('category_ticket', ['category_id' => $category->id, 'ticket_id' => $ticket->id]);
        });
    }

    public function testUpdateInValidData(): void
    {
        $user= \App\Models\User::factory()->create();
        $this->actingAs($user);

        $ticket= \App\Models\Ticket::factory()->create();

        $data = [
            "name" => fake()->title,
            "description" => fake()->title,
            "category_ids" => [], //invalid
            "status" => TicketStatusEnum::OPENED->value
        ];

        $response= $this->put("api/v1/ticket/edit/{$ticket->id}", $data, ['Accept' => 'application/json']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertDatabaseMissing('tickets', ["name"=> $data["name"]]);
    }
}
