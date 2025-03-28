<?php

// database/factories/PlantFactory.php
namespace Database\Factories;

use App\Models\Plant;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlantFactory extends Factory
{
    protected $model = Plant::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'slug' => $this->faker->slug,
            'category_id' => \App\Models\Category::factory(),
        ];
    }
}

