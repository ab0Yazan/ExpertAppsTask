<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            "name"=>$this->faker->name(),
            "description"=>$this->faker->text(),
            "status" => true,
            "user_id"=> \App\Models\User::inRandomOrder()->first()->id,
        ];
    }
}
