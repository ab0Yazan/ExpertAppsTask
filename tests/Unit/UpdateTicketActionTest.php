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

        $categories= Category::limit(3)->get();

        $dataToUpdate = [
            'categories' => $categories,
            'name' => 'updated version',
            'description' => 'updated version',
            'status' => TicketStatusEnum::CLOSED,
        ];

        $action= resolve(UpdateTicketAction::class);
        $ticket= $action->execute(UpsertTicketDto::fromArray($dataToUpdate), $ticket);

        $this->assertInstanceOf(TicketDetailsDto::class, $ticket);
        $this->assertDatabaseHas('tickets', ["name" => $ticket->name,
            "description" => $ticket->description,
            "status" => TicketStatusEnum::CLOSED]);
        $categories->each(function ($category) use ($ticket) {
            $this->assertDatabaseHas('category_ticket', ['category_id' => $category->id, 'ticket_id' => $ticket->id]);
        });

    }
}
