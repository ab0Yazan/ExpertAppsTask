<?php

namespace Tests\Unit;


use App\Actions\CreateTicketAction;
use App\DataTransferObjects\TicketDetailsDto;
use App\DataTransferObjects\UpsertTicketDto;
use App\Enums\TicketStatusEnum;
use App\Models\Category;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateTicketActionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CategorySeeder::class);
    }

    public function testTicketActionCanBeCreated()
    {
        $user= \App\Models\User::factory()->create();
        $this->actingAs($user);

        $categories= Category::inRandomOrder()->limit(3)->get();
        $data = [
            "name" => fake()->title,
            "description" => fake()->title,
            "status" => TicketStatusEnum::OPENED,
            "categories" => $categories,
            "user" => $user,
        ];
        $action= resolve(CreateTicketAction::class);



        /**i prefer return dto for decoupling **/
        /**@TicketDetailsDto $ticket*/
        $ticket= $action->execute(UpsertTicketDto::fromArray($data), $user);

        $this->assertInstanceOf(TicketDetailsDto::class, $ticket);
        $this->assertDatabaseHas('tickets', ["name" => $ticket->name]);
        $categories->each(function ($category) use ($ticket) {
            $this->assertDatabaseHas('category_ticket', ['category_id' => $category->id, 'ticket_id' => $ticket->id]);
        });
    }

}
