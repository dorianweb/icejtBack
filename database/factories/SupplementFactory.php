<?php

namespace Database\Factories;

use App\Models\Supplement;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplementFactory extends Factory
{
    var  $tab = [Supplement::UNIT_LIQUID, 'g'];
    var  $tabType = [1, 2];
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Supplement::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $unit = $this->faker->numberBetween(0, 1);
        return [
            'name' =>  $this->faker->name,
            'weight' => $this->faker->numberBetween(10, 20),
            'unit' => $this->tab[$unit],
            'supplement_type_id' => $this->tabType[$unit]

        ];
    }
}
