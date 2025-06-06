<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Ticket;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class FilterTicketFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CategorySeeder::class);
    }

    public function testFilterTickets(): void
    {
        $user= \App\Models\User::factory()->create();
        $this->actingAs($user);

        $category1 = Category::first();
        $category2 = Category::orderBy('id','desc')->first();

        Ticket::Factory()->count(5)->create()->each(function ($ticket) use ($category1) {
            $ticket->categories()->attach([$category1->id]);
        });
        Ticket::Factory()->count(5)->create()->each(function ($ticket) use ($category2) {
            $ticket->categories()->attach([$category2->id]);
        });

        $response= $this->get("api/v1/ticket/list?name={$category1->name}", ['Accept' => 'application/json']);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertCount(5, $response->json('data'));
    }
}
