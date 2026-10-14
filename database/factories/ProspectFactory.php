<?php

namespace Database\Factories;

use App\Models\Prospect;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Prospect>
 */
class ProspectFactory extends Factory
{
    protected $model = Prospect::class;

    protected static array $sources = [
        'Facebook', 'Instagram', 'TikTok', 'YouTube', 'Twitter / X',
        'LinkedIn', 'Referral', 'Friend', 'Family', 'Website', 'Event', 'Other',
    ];

    protected static array $hotness = ['Hot', 'Warm', 'Cold'];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->optional(0.7)->phoneNumber(),
            'source' => $this->faker->randomElement(static::$sources),
            'hotness' => $this->faker->randomElement(static::$hotness),
            'next_follow_up' => $this->faker->optional(0.6)->dateTimeBetween('-3 days', '+14 days'),
            'notes' => $this->faker->optional(0.5)->sentence(12),
        ];
    }

    /**
     * Only an email on file, no phone number.
     */
    public function emailOnly(): static
    {
        return $this->state(fn () => ['phone' => null]);
    }

    /**
     * Only a phone number on file, no email.
     */
    public function phoneOnly(): static
    {
        return $this->state(fn () => ['email' => null, 'phone' => $this->faker->phoneNumber()]);
    }

    public function hot(): static
    {
        return $this->state(['hotness' => 'Hot']);
    }

    public function warm(): static
    {
        return $this->state(['hotness' => 'Warm']);
    }

    public function cold(): static
    {
        return $this->state(['hotness' => 'Cold']);
    }

    /**
     * A follow-up that's already past due.
     */
    public function overdue(): static
    {
        return $this->state(fn () => ['next_follow_up' => $this->faker->dateTimeBetween('-14 days', '-1 day')]);
    }

    /**
     * A follow-up due within the next few days.
     */
    public function dueSoon(): static
    {
        return $this->state(fn () => ['next_follow_up' => $this->faker->dateTimeBetween('now', '+5 days')]);
    }

    /**
     * No follow-up scheduled at all.
     */
    public function noFollowUp(): static
    {
        return $this->state(fn () => ['next_follow_up' => null]);
    }
}
