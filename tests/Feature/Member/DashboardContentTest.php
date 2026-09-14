<?php

use App\Livewire\Dashboard\DashboardHome;
use App\Livewire\Dashboard\NotificationDropdown;
use App\Livewire\Dashboard\SponsorshipTree;
use App\Livewire\Member\ContentHub;
use App\Models\Activity;
use App\Models\Invitation;
use App\Models\MemberEvent;
use App\Models\MemberEventRegistration;
use App\Models\MemberResource;
use App\Models\MemberResourceInteraction;
use App\Models\SponsorRelationship;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Livewire;

test('invite link page shows live invitation data', function () {
    /** @var User $sponsor */
    $sponsor = User::factory()->create([
        'username' => 'sponsor.one',
        'email' => 'sponsor.one@example.test',
    ]);

    /** @var User $user */
    $user = User::factory()->create([
        'username' => 'member.one',
        'email' => 'member.one@example.test',
        'sponsor_id' => $sponsor->id,
    ]);

    $user->profile()->update(['invite_code' => 'MEMBER-REF']);

    Invitation::create([
        'inviter_id' => $user->id,
        'sponsor_id' => $sponsor->id,
        'email' => 'lead.one@example.test',
        'invite_code' => 'MEMBER-REF-001',
        'invite_link' => url('/register?ref=MEMBER-REF'),
        'status' => 'accepted',
        'sent_at' => now()->subDay(),
        'accepted_at' => now()->subHours(12),
        'last_viewed_at' => now()->subHours(18),
    ]);

    $this->actingAs($user)
        ->get(route('member.invite-link'))
        ->assertOk()
        ->assertSee('MEMBER-REF')
        ->assertSee('lead.one@example.test')
        ->assertSee('Accepted');
});

test('notifications page shows stored notifications', function () {
    /** @var User $user */
    $user = User::factory()->create();

    DB::table('notifications')->insert([
        'id' => (string) Str::uuid(),
        'type' => 'security.alert',
        'notifiable_type' => User::class,
        'notifiable_id' => $user->id,
        'data' => json_encode([
            'type' => 'security',
            'title' => 'Security alert',
            'message' => 'A new device signed into your dashboard.',
        ]),
        'read_at' => null,
        'created_at' => now()->subMinutes(30),
        'updated_at' => now()->subMinutes(30),
    ]);

    $this->actingAs($user)
        ->get(route('member.notifications'))
        ->assertOk()
        ->assertSee('Security alert')
        ->assertSee('Unread');
});

test('dashboard home renders live sponsor and event data', function () {
    /** @var User $sponsor */
    $sponsor = User::factory()->create([
        'username' => 'sponsor.live',
        'email' => 'sponsor.live@example.test',
    ]);

    /** @var User $user */
    $user = User::factory()->create([
        'username' => 'member.live',
        'email' => 'member.live@example.test',
        'sponsor_id' => $sponsor->id,
    ]);

    SponsorRelationship::create([
        'member_id' => $user->id,
        'sponsor_id' => $sponsor->id,
        'sponsor_code' => 'SP-1001',
        'is_current' => true,
        'linked_at' => now()->subMonths(2),
        'metadata' => ['source' => 'test'],
    ]);

    Invitation::create([
        'inviter_id' => $user->id,
        'sponsor_id' => $sponsor->id,
        'email' => 'lead.two@example.test',
        'invite_code' => 'LIVE-001',
        'invite_link' => url('/register?ref=LIVE'),
        'status' => 'pending',
        'sent_at' => now()->subDay(),
        'last_viewed_at' => now()->subHours(10),
    ]);

    Activity::create([
        'user_id' => $user->id,
        'type' => 'event',
        'title' => 'Webinar reserved',
        'description' => 'A seat was reserved for the Wednesday Market Structure Lab.',
        'metadata' => ['source' => 'test'],
        'ip_address' => '127.0.0.1',
    ]);

    $this->actingAs($user);

    Livewire::test(DashboardHome::class)
        ->assertSee('sponsor.live')
        ->assertSee('Webinar reserved')
        ->assertSee('Pending Invitations');
});

test('content hub can resend invites and mark notifications as read', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $invite = Invitation::create([
        'inviter_id' => $user->id,
        'sponsor_id' => $user->id,
        'email' => 'followup@example.test',
        'invite_code' => 'FOLLOWUP-001',
        'invite_link' => url('/register?ref=FOLLOWUP'),
        'status' => 'pending',
        'sent_at' => now()->subDays(2),
    ]);

    $notificationId = (string) Str::uuid();

    DB::table('notifications')->insert([
        'id' => $notificationId,
        'type' => 'member.joined',
        'notifiable_type' => User::class,
        'notifiable_id' => $user->id,
        'data' => json_encode(['type' => 'member', 'title' => 'New member joined', 'message' => 'A new member joined your line.']),
        'read_at' => null,
        'created_at' => now()->subHour(),
        'updated_at' => now()->subHour(),
    ]);

    $this->actingAs($user);

    Livewire::test(ContentHub::class, ['page' => 'invite-link', 'title' => 'My Invite Link', 'description' => ''])
        ->call('resendInvite', $invite->id)
        ->assertSet('statusMessage', 'Invite resent and follow-up activity recorded.');

    expect($invite->fresh()->status)->toBe('sent');

    Livewire::test(ContentHub::class, ['page' => 'notifications', 'title' => 'Notifications', 'description' => ''])
        ->call('markNotificationAsRead', $notificationId)
        ->assertSet('statusMessage', 'Notification marked as read.');

    expect(DB::table('notifications')->where('id', $notificationId)->value('read_at'))->not->toBeNull();
});

test('content hub can reserve events and mark resources as reviewed', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $event = MemberEvent::create([
        'title' => 'Weekly Lab',
        'slug' => 'weekly-lab',
        'summary' => 'Weekly member lab session.',
        'starts_at' => now()->addDay(),
        'location' => 'Zoom',
        'audience' => 'All Members',
        'cta_label' => 'Reserve seat',
        'is_published' => true,
    ]);

    $resource = MemberResource::create([
        'title' => 'Risk Guide',
        'slug' => 'risk-guide',
        'summary' => 'Risk guide for members.',
        'category' => 'education',
        'audience' => 'All Members',
        'format' => 'pdf',
        'resource_url' => url('/junk.html'),
        'is_published' => true,
    ]);

    $this->actingAs($user);

    Livewire::test(ContentHub::class, ['page' => 'events', 'title' => 'Events', 'description' => ''])
        ->call('reserveEvent', $event->id)
        ->assertSet('statusMessage', 'Event reservation saved.');

    expect(MemberEventRegistration::query()->where('member_event_id', $event->id)->where('user_id', $user->id)->exists())->toBeTrue();

    Livewire::test(ContentHub::class, ['page' => 'resources', 'title' => 'Resources', 'description' => ''])
        ->call('markResourceReviewed', $resource->id)
        ->assertSet('statusMessage', 'Resource review saved.');

    expect(MemberResourceInteraction::query()->where('member_resource_id', $resource->id)->where('user_id', $user->id)->value('status'))->toBe('reviewed');
});

test('notification dropdown can mark all notifications as read', function () {
    /** @var User $user */
    $user = User::factory()->create();

    DB::table('notifications')->insert([
        [
            'id' => (string) Str::uuid(),
            'type' => 'training.reminder',
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'data' => json_encode(['type' => 'training', 'title' => 'Training reminder', 'message' => 'Complete your module.']),
            'read_at' => null,
            'created_at' => now()->subMinutes(20),
            'updated_at' => now()->subMinutes(20),
        ],
        [
            'id' => (string) Str::uuid(),
            'type' => 'security.alert',
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'data' => json_encode(['type' => 'security', 'title' => 'Security alert', 'message' => 'Review a new sign-in.']),
            'read_at' => null,
            'created_at' => now()->subMinutes(10),
            'updated_at' => now()->subMinutes(10),
        ],
    ]);

    $this->actingAs($user);

    Livewire::test(NotificationDropdown::class)
        ->call('markAllAsRead')
        ->assertSee('0 unread');

    expect(DB::table('notifications')->where('notifiable_id', $user->id)->whereNull('read_at')->count())->toBe(0);
});

test('sponsorship explorer can focus a branch and search members', function () {
    /** @var User $root */
    $root = User::factory()->create([
        'username' => 'root.member',
        'email' => 'root.member@example.test',
    ]);

    /** @var User $childOne */
    $childOne = User::factory()->create([
        'username' => 'child.one',
        'email' => 'child.one@example.test',
        'sponsor_id' => $root->id,
    ]);

    /** @var User $childTwo */
    $childTwo = User::factory()->create([
        'username' => 'child.two',
        'email' => 'child.two@example.test',
        'sponsor_id' => $root->id,
    ]);

    /** @var User $grandChild */
    $grandChild = User::factory()->create([
        'username' => 'grand.child',
        'email' => 'grand.child@example.test',
        'sponsor_id' => $childOne->id,
    ]);

    $this->actingAs($root);

    Livewire::test(SponsorshipTree::class)
        ->assertSee('child.one')
        ->call('focusNode', $childOne->id)
        ->assertSet('selectedNodeId', $childOne->id)
        ->assertSee('grand.child')
        ->set('search', 'child.two')
        ->assertSee('child.two');
});

test('sponsorship explorer does not treat a self-sponsored member as their own child', function () {
    /** @var User $root */
    $root = User::factory()->create([
        'username' => 'self.root',
        'email' => 'self.root@example.test',
    ]);
    $root->forceFill(['sponsor_id' => $root->id])->save();

    /** @var User $child */
    $child = User::factory()->create([
        'username' => 'self.child',
        'email' => 'self.child@example.test',
        'sponsor_id' => $root->id,
    ]);

    $this->actingAs($root);

    $this->get('/member/genealogy')
        ->assertOk()
        ->assertSee('self.root')
        ->assertSee('self.child');

    $component = Livewire::test(SponsorshipTree::class);

    expect($component->get('nodesById')[$root->id]['parent_id'])->toBeNull();

    $childIds = collect($component->get('nodesById'))
        ->filter(fn (array $node) => ($node['parent_id'] ?? null) === $root->id)
        ->pluck('id')
        ->all();

    expect($childIds)
        ->toContain($child->id)
        ->not->toContain($root->id);
});