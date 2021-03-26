<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'state' => $this->faker->randomElement(['public', 'private']),
            'information' => $this->faker->paragraph(1),
            'place' => $this->faker->locale,
            'visibility' => $this->faker->numberBetween(0, 1),
        ];
    }
}
