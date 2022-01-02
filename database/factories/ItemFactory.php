<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'id_category' => $this->faker->numberBetween(0, 10),
            'location' => Str::random(1) . '-' . Str::random(1) . '-' . Str::random(1) . '-' . Str::random(1),
            'quantity' => $this->faker->randomDigit(),
            'description' => $this->faker->realText(150)
        ];

    }
}
