<?php

namespace Tests\Unit;


use App\Enums\TicketStatusEnum;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TicketModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function testTicketCanBeCreatedWithAttributes(): void
    {
        $user = User::factory()->create();
        $data = [
            'user_id' => $user->id,
            'name' => 'Test Ticket Name',
            'status' => TicketStatusEnum::OPENED,
            'description' => 'test description',
        ];

        $ticket = Ticket::create($data);

        $this->assertInstanceOf(Ticket::class, $ticket);
        $this->assertDatabaseHas('tickets', ['name' => $data['name'], 'user_id' => $user->id]);
        $this->assertEquals($data['name'], $ticket->name);
        $this->assertEquals($data['description'], $ticket->description);
        $this->assertEquals($user->id, $ticket->user_id);
        $this->assertEquals(TicketStatusEnum::OPENED, $ticket->status);
    }

    public function testTicketBelongsToUser(): void
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $ticket->user);
        $this->assertEquals($user->id, $ticket->user->id);
    }

    public function testTicketBelongsToManyCategories(): void
    {
        $ticket = Ticket::factory()->create();
        $category1 = Category::factory()->create(['name' => 'Support']);
        $category2 = Category::factory()->create(['name' => 'Billing']);

        $ticket->categories()->attach([$category1->id, $category2->id]);

        $this->assertCount(2, $ticket->categories);
        $this->assertTrue($ticket->categories->contains($category1));
        $this->assertTrue($ticket->categories->contains($category2));
    }

    public function testTicketStatusIsCastedToEnum(): void
    {
        $ticket = Ticket::factory()->create(['status' => 0]);

        $this->assertInstanceOf(TicketStatusEnum::class, $ticket->status);
        $this->assertEquals(TicketStatusEnum::CLOSED, $ticket->status);

        $ticket->status = TicketStatusEnum::CLOSED;
        $ticket->save();

        $this->assertEquals(0, $ticket->getAttributes()['status']);
        $this->assertEquals(TicketStatusEnum::CLOSED, $ticket->fresh()->status);
    }

    public function testTicketEagerLoadsCategoriesByDefault(): void
    {
        $category = Category::factory()->create();
        $ticket = Ticket::factory()->create();
        $ticket->categories()->attach($category->id);

        $retrievedTicket = Ticket::find($ticket->id);

        $this->assertTrue($retrievedTicket->relationLoaded('categories'));
        $this->assertCount(1, $retrievedTicket->categories);
        $this->assertEquals($category->id, $retrievedTicket->categories->first()->id);
    }

    public function testPersistMethodSavesTicketAndSyncsCategories(): void
    {
        DB::beginTransaction();

        $user = User::factory()->create();
        $category1 = Category::factory()->create(['name' => 'Tech Issue']);
        $category2 = Category::factory()->create(['name' => 'Feature Request']);
        $categoryToDetach = Category::factory()->create(['name' => 'Old Category']);

        $ticket = Ticket::factory()->make([
            'user_id' => $user->id,
            'name' => 'Ticket for Persist Test',
            'status' => TicketStatusEnum::OPENED,
            'description' => 'Testing persist method.'
        ]);

        $ticket->setRelation('categories', collect([$category1, $category2]));

        $persistedTicket = $ticket->persist();

        $this->assertTrue($persistedTicket->exists);
        $this->assertDatabaseHas('tickets', ['id' => $persistedTicket->id, 'name' => 'Ticket for Persist Test']);

        $this->assertCount(2, $persistedTicket->categories);
        $this->assertTrue($persistedTicket->categories->contains($category1));
        $this->assertTrue($persistedTicket->categories->contains($category2));
        $this->assertFalse($persistedTicket->categories->contains($categoryToDetach));

        DB::rollBack();
    }
}
