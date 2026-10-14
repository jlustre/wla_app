<?php

namespace Database\Factories;

use App\Models\Calendar;
use App\Models\CalendarEvent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CalendarEvent>
 */
class CalendarEventFactory extends Factory
{
    protected $model = CalendarEvent::class;

    public function configure(): static
    {
        // If a test/seeder doesn't explicitly pick a calendar, attach the
        // event to that user's default calendar (creating one if needed)
        // so ownership checks (calendar_id-based) line up with user_id.
        return $this->afterMaking(function (CalendarEvent $event) {
            if ($event->calendar_id) {
                return;
            }

            $calendar = Calendar::firstOrCreate(
                ['user_id' => $event->user_id, 'is_default' => true],
                ['name' => 'My Calendar', 'color' => 'peacock']
            );

            $event->calendar_id = $calendar->id;
        });
    }

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-1 week', '+3 weeks');
        $start->setTime((int) $this->faker->numberBetween(8, 17), $this->faker->randomElement([0, 15, 30, 45]));
        $end = (clone $start)->modify('+'.$this->faker->randomElement([30, 45, 60, 90]).' minutes');

        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->optional(0.6)->paragraph(),
            'location' => $this->faker->optional(0.5)->city(),
            'start_at' => $start,
            'end_at' => $end,
            'all_day' => false,
            'color' => $this->faker->randomElement(array_keys(CalendarEvent::colorPalette())),
            'reminder_minutes' => $this->faker->optional(0.5)->randomElement([10, 30, 60, 1440]),
        ];
    }

    public function allDay(): static
    {
        return $this->state(function (array $attributes) {
            $date = $this->faker->dateTimeBetween('-1 week', '+3 weeks')->format('Y-m-d');

            return [
                'start_at' => $date.' 00:00:00',
                'end_at' => $date.' 23:59:59',
                'all_day' => true,
            ];
        });
    }
}
