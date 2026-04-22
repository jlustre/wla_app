<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LastActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $actions = [
            'Added as prospect',
            'Contacted',
            'Invited to watch video',
            'Video sent',
            'Video opened',
            'Watched 50% of video',
            'Watched 75% of video',
            'Watched 100% of video',
            'Follow-up sent',
            'Follow-up received',
            'Objection: No time',
            'Objection: Not interested',
            'Objection: No money',
            'Objection: Already joined elsewhere',
            'Ready to join',
            'Joined',
            'Scheduled call',
            'Call completed',
            'Sent compensation summary',
            'Sent opportunity overview',
            'Sent WLA intro page',
            'Sent reminder',
            'No reply',
            'Inactive',
            'Converted',
            'Other',
        ];

        foreach ($actions as $action) {
            DB::table('last_actions')->updateOrInsert([
                'action' => $action
            ]);
        }
    }
}
