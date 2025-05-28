<?php

namespace Tests\Unit;

use App\Actions\CategoryListAction;
use App\Http\Controllers\CategoryListController;
use App\Http\Requests\CategoryListRequest;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryListControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        resolve(CategorySeeder::class)->run();
    }

    public function testListCategoryTree(): void
    {
        $request= new CategoryListRequest();
        $action= resolve(CategoryListAction::class);
        $controller= new CategoryListController();
        $response = $controller->__invoke($request, $action);
        $data= $response->getData(true);
        $this->assertTrue("success"==$data['status']);
    }
}
