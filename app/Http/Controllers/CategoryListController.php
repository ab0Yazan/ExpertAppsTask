<?php

namespace App\Http\Controllers;

use App\Actions\CategoryListAction;
use App\Http\Requests\CategoryListRequest;

class CategoryListController extends Controller
{
    public function __invoke(CategoryListRequest $request, CategoryListAction $action)
    {
        $categories= $action->execute($request->getFilters());
        return self::apiResponse()->ok($categories);
    }
}
