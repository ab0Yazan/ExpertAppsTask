<?php

namespace Tests\Feature;

use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class CategoryListFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CategorySeeder::class);
    }

    public function testListCategories(): void
    {
        $response= $this->get("api/lookups/categories",  ['Accept' => 'application/json']);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            "status",
            "data" => [[
                 "id","name", "children"
            ]]
        ]);
    }
}
