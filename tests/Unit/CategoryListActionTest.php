<?php

namespace Tests\Unit;

use App\Actions\CategoryListAction;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CategoryListActionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        resolve(CategorySeeder::class)->run();
    }

    public function testCanListCategoriesAndChilderns(): void
    {
        $action = resolve(CategoryListAction::class);
        $categories= $action->execute();
        $tree= $categories->toArray();
        $this->assertCount(2, $tree[0]['children']);
        $this->assertCount(2, $tree[0]['children'][0]['children']);
    }

    public function testCanListCategoriesAndChildernsFromCache(): void
    {
        Cache::shouldReceive('remember')
            ->once()
            ->with('category_tree', \Mockery::type(\DateTimeInterface::class), \Closure::class)
            ->andReturn(collect([]));

        $action = resolve(CategoryListAction::class);

        $dto = $action->execute();

        $this->assertEquals([], $dto->toArray());
    }
}
