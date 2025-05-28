<?php

namespace Tests\Unit;

use App\Actions\UpdateTicketAction;
use App\DataTransferObjects\TicketDetailsDto;
use App\DataTransferObjects\UpdateTicketDto;
use App\DataTransferObjects\UpsertTicketDto;
use App\Enums\TicketStatusEnum;
use App\Models\Category;
use App\Models\Ticket;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTicketActionTest extends TestCase
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

        $ticket= Ticket::factory()->create();

        $category= Category::inRandomOrder()->first();

        $dataToUpdate = [
            'category' => $category,
            'name' => 'updated version',
            'description' => 'updated version',
            'status' => TicketStatusEnum::CLOSED,
        ];

        $action= resolve(UpdateTicketAction::class);
        $ticket= $action->execute(UpsertTicketDto::fromArray($dataToUpdate), $ticket);

        $this->assertInstanceOf(TicketDetailsDto::class, $ticket);
        $this->assertDatabaseHas('tickets', ["name" => $ticket->name,
            "description" => $ticket->description,
            "category_id"=> $category->id,
            "status" => TicketStatusEnum::CLOSED]);
    }
}
