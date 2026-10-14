<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\SponsorRelationship;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReassignmentDemoSeeder extends Seeder
{
    /**
     * Seeds a couple of real, historical sponsor reassignments so the admin
     * dashboard's reassignment audit and fear-of-loss sections have real
     * (non-empty) data to render instead of relying on an empty table.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@wla.com')->first();

        $cases = [
            ['member' => 6, 'original_sponsor' => 3, 'ended_at' => now()->subDays(3), 'reason' => 'Original sponsor inactive in company positioning window'],
            ['member' => 8, 'original_sponsor' => 4, 'ended_at' => now()->subDays(6), 'reason' => 'Positioning gap detected during audit review'],
        ];

        foreach ($cases as $case) {
            $member = User::find($case['member']);
            $originalSponsor = User::find($case['original_sponsor']);

            if (! $member || ! $originalSponsor) {
                continue;
            }

            $relationship = SponsorRelationship::updateOrCreate(
                ['member_id' => $member->id, 'sponsor_id' => $originalSponsor->id, 'is_current' => false],
                [
                    'sponsor_code' => 'SP-'.str_pad((string) $originalSponsor->id, 4, '0', STR_PAD_LEFT),
                    'linked_at' => $case['ended_at']->copy()->subMonths(2),
                    'ended_at' => $case['ended_at'],
                    'metadata' => ['source' => 'reassignment-demo', 'reason' => $case['reason']],
                ]
            );

            $auditLog = AuditLog::updateOrCreate(
                ['actor_id' => $admin?->id, 'event' => 'sponsor.reassigned', 'auditable_type' => User::class, 'auditable_id' => $member->id],
                [
                    'old_values' => ['sponsor_id' => $originalSponsor->id],
                    'new_values' => ['sponsor_id' => $member->sponsor_id],
                    'metadata' => ['source' => 'reassignment-demo', 'reason' => $case['reason'], 'relationship_id' => $relationship->id],
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'Seeder',
                ]
            );

            $auditLog->forceFill(['created_at' => $case['ended_at'], 'updated_at' => $case['ended_at']])->save();
        }
    }
}
