<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mainCategories = collect([
            'Technical Support',
            'Billing',
            'Account Issues',
            'Feedback',
            'Sales',
        ]);

        $mainCategories->each(function ($catName) {
            Category::create(['name' => $catName]);
        });

        Category::all()->each(function ($parent) {
            //level 1
            $children = Category::factory()
                ->count(2)
                ->withParent($parent->id)
                ->create();

            //level 2
            $children->each(function ($child) {
                Category::factory()
                    ->count(2)
                    ->withParent($child->id)
                    ->create();
            });
        });
    }
}
