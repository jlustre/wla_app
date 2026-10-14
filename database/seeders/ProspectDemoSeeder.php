<?php

namespace Database\Seeders;

use App\Models\Prospect;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Curated prospect data for exercising the "My Prospects" page
 * (app/Livewire/Member/ProspectsManager.php): every hotness level, an
 * overdue follow-up, a due-soon follow-up, one row per source (so the
 * filter dropdown has real variety), contact-method edge cases, and a
 * batch of random rows so pagination has something to page through.
 *
 * Safe to re-run: it clears its own previously-seeded rows (tagged by the
 * @demo.test email domain, or a fixed phone number for the one row that
 * deliberately has no email) before inserting fresh ones.
 */
class ProspectDemoSeeder extends Seeder
{
    private const PHONE_ONLY_NUMBER = '+1 555-0199-0001';

    public function run(): void
    {
        $user = User::where('username', 'jclustre')->first()
            ?? User::where('email', 'jclustre@gmail.com')->first()
            ?? User::first();

        if (! $user) {
            $this->command?->warn('ProspectDemoSeeder: no user found to attach demo prospects to, skipping.');

            return;
        }

        // Clear any demo rows from a previous run so this stays idempotent.
        // Most rows are tagged via an @demo.test email, but the "phone only"
        // row has no email, so it's matched by its fixed demo phone instead.
        Prospect::where('user_id', $user->id)
            ->where(function ($query) {
                $query->where('email', 'like', '%@demo.test')
                    ->orWhere('phone', self::PHONE_ONLY_NUMBER);
            })
            ->delete();

        $sources = [
            'Facebook', 'Instagram', 'TikTok', 'YouTube', 'Twitter / X',
            'LinkedIn', 'Referral', 'Friend', 'Family', 'Website', 'Event', 'Other',
        ];

        // One prospect per source, cycling through hotness, so the filter
        // dropdowns have rich, predictable options to test against.
        foreach ($sources as $i => $source) {
            Prospect::factory()
                ->for($user)
                ->state([
                    'first_name' => 'Demo',
                    'last_name' => 'From '.$source,
                    'email' => 'demo.'.str($source)->slug().'@demo.test',
                    'source' => $source,
                    'hotness' => ['Hot', 'Warm', 'Cold'][$i % 3],
                ])
                ->create();
        }

        // Deliberate edge cases for the UI states the page renders.
        Prospect::factory()->for($user)->hot()->overdue()->create([
            'first_name' => 'Overdue', 'last_name' => 'Hot Lead',
            'email' => 'overdue.hot@demo.test',
            'notes' => 'Should show in red as overdue on the Next Follow-up column.',
        ]);

        Prospect::factory()->for($user)->warm()->dueSoon()->create([
            'first_name' => 'Due Soon', 'last_name' => 'Warm Lead',
            'email' => 'duesoon.warm@demo.test',
            'notes' => 'Should count toward "Follow-ups Due (7d)" and show amber.',
        ]);

        Prospect::factory()->for($user)->cold()->noFollowUp()->create([
            'first_name' => 'No Follow-up', 'last_name' => 'Cold Lead',
            'email' => 'nofollowup.cold@demo.test',
        ]);

        Prospect::factory()->for($user)->emailOnly()->create([
            'first_name' => 'Email', 'last_name' => 'Only',
            'email' => 'emailonly@demo.test',
        ]);

        Prospect::factory()->for($user)->phoneOnly()->create([
            'first_name' => 'Phone', 'last_name' => 'Only',
            'phone' => self::PHONE_ONLY_NUMBER,
        ]);

        Prospect::factory()->for($user)->hot()->create([
            'first_name' => 'Long', 'last_name' => 'Notes',
            'email' => 'longnotes@demo.test',
            'notes' => 'Met at the regional conference, very engaged during the Q&A, '
                .'already watched the overview video twice and asked for the compensation '
                .'plan breakdown by email. Mentioned a target start date next month and '
                .'wants to loop in their spouse before signing up.',
        ]);

        // A batch of plain randomized rows so pagination has enough to page through.
        Prospect::factory()
            ->for($user)
            ->count(12)
            ->sequence(fn ($seq) => ['email' => 'demo.batch'.$seq->index.'@demo.test'])
            ->create();

        $this->command?->info('ProspectDemoSeeder: seeded demo prospects for '.$user->username.'.');
    }
}
