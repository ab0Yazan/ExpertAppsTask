<?php

namespace Tests\Feature;

use App\Enums\TicketStatusEnum;
use App\Models\Category;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $categories = Category::inRandomOrder()->limit(3)->get();

        $data = [
            "name" => fake()->title,
            "description" => fake()->title,
            "category_ids" => $categories->pluck('id')->toArray(),
            "status" => TicketStatusEnum::OPENED->value,
            "user" => $user,
        ];

        $response= $this->post("api/v1/ticket/add", $data, ['Accept' => 'application/json']);
        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('tickets', ["name"=> $data["name"]]);
        $categories->each(function ($category) {
            $this->assertDatabaseHas('category_ticket', ['category_id' => $category->id]);
        });
    }

    public function testStoreInValidData(): void
    {
        $user= \App\Models\User::factory()->create();
        $this->actingAs($user);

        $data = [
            "name" => fake()->title,
            "description" => fake()->title,
            "status" => TicketStatusEnum::OPENED->value,
            "category_ids" => [] //invalid
        ];

        $response= $this->post("api/v1/ticket/add", $data, ['Accept' => 'application/json']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertDatabaseMissing('tickets', ["name"=> $data["name"]]);
    }
}
