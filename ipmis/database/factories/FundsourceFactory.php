<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fundsource>
 */
class FundsourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = \App\Models\Fundsource::class;
    public function definition(): array
    {
        return [
            'fundsource' => fake()->word,
            'fundsource_acc' => fake()->text(5),
        ];
    }
}
