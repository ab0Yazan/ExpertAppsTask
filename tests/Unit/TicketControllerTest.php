<?php

namespace Tests\Unit;

use App\Actions\CreateTicketAction;
use App\Actions\ListTicketAction;
use App\Actions\UpdateTicketAction;
use App\Enums\TicketStatusEnum;
use App\Http\Controllers\TicketController;
use App\Http\Requests\ListTicketRequest;
use App\Http\Requests\UpsertTicketRequest;
use App\Models\Category;
use App\Models\Ticket;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CategorySeeder::class);
    }

    public function testCreateTicketMethod(): void
    {
        $user= \App\Models\User::factory()->create();
        $this->actingAs($user);
        $data = [
            "name" => fake()->title,
            "description" => fake()->title,
            "category_id" => Category::inRandomOrder()->first()->id,
            "status" => TicketStatusEnum::OPENED->value
        ];
        $request= new UpsertTicketRequest($data);
        $controller = new TicketController();
        $action= resolve(CreateTicketAction::class);
        $response= $controller->store($request, $action);
        $arr= $response->getData(true);
        $this->assertEquals($data["name"], $arr["data"]["name"]);
    }

    public function testStoreFailsIfUserNotAuthenticated(): void
    {
        $data = [
            "name" => fake()->title,
            "description" => fake()->title,
            "category_id" => Category::inRandomOrder()->first()->id,
            "status" => TicketStatusEnum::OPENED->value
        ];

        $request = new UpsertTicketRequest($data);
        $response= (new TicketController())->store($request, resolve(CreateTicketAction::class));

        $this->assertEquals("error", $response->getData(true)["status"]);
    }

    public function testItUpdatesTicketValidData(): void
    {
        $user= \App\Models\User::factory()->create();
        $this->actingAs($user);

        $ticket= Ticket::factory()->create();

        $data = [
            "name" => fake()->title,
            "description" => fake()->title,
            "category_id" => Category::inRandomOrder()->first()->id,
            "status" => TicketStatusEnum::OPENED->value
        ];

        $request= new UpsertTicketRequest($data);
        $controller = new TicketController();
        $action= resolve(UpdateTicketAction::class);
        $response= $controller->update($request, $action, $ticket);
        $arr= $response->getData(true);
        $this->assertEquals($data["name"], $arr["data"]["name"]);
    }

    public function testListTicketsByFilter(): void
    {
        $user= \App\Models\User::factory()->create();
        $this->actingAs($user);

        Ticket::factory()->count(10)->create();

        $request= new ListTicketRequest();
        $action= resolve(ListTicketAction::class);
        $controller = new TicketController();
        $response= $controller->filter($request, $action);
        $arr= $response->getData(true);
        $this->assertCount(10, $arr["data"]);
        $this->assertEquals("success", $arr["status"]);

    }
}
