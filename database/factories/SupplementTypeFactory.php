<?php

namespace Database\Factories;

use App\Models\SupplementType;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplementTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SupplementType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'price' => $this->faker->numberBetween(30, 150),
        ];
    }
}
