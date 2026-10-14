<?php

namespace Database\Factories;

use App\Models\Calendar;
use App\Models\CalendarEvent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Calendar>
 */
class CalendarFactory extends Factory
{
    protected $model = Calendar::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => 'My Calendar',
            'color' => $this->faker->randomElement(array_keys(CalendarEvent::colorPalette())),
            'is_default' => true,
        ];
    }
}
