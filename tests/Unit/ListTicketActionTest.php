<?php

namespace Tests\Unit;


use App\Actions\ListTicketAction;
use App\DataTransferObjects\TicketDetailsDto;
use App\DataTransferObjects\TicketFilterDto;
use App\Models\Category;
use App\Models\Ticket;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListTicketActionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CategorySeeder::class);
    }

    public function testListAllTicketAction()
    {
        $user= \App\Models\User::factory()->create();
        $this->actingAs($user);

        Ticket::Factory()->count(50)->create();

        $action= resolve(ListTicketAction::class);
        $dto = new TicketFilterDto(null);
        $tickets= $action->execute($dto);
        $this->assertInstanceOf(TicketDetailsDto::class, $tickets->first());
        $this->assertCount(50, $tickets);
    }

    public function testListFilterTicketAction()
    {
        $user= \App\Models\User::factory()->create();
        $this->actingAs($user);

        $category= Category::inRandomOrder()->first();
        Ticket::Factory()->count(10)->create(['category_id' => $category->id]);

        $action= resolve(ListTicketAction::class);
        $dto = new TicketFilterDto(Ticket::inRandomOrder()->first()->category->name);
        $tickets= $action->execute($dto);
        $this->assertInstanceOf(TicketDetailsDto::class, $tickets->first());
        $this->assertCount(10, $tickets);
    }

}
