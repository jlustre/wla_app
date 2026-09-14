<?php

use App\Models\User;
use App\Support\MemberSidebarMenu;
use Spatie\Permission\Models\Role;

test('member workspace uses spa navigation and persists chrome', function () {
    $user = User::factory()->create();

    $html = $this->actingAs($user)
        ->get(route('member.sponsor'))
        ->assertOk()
        ->assertSee('x-persist="member-sidebar-desktop"', false)
        ->assertSee('x-persist="member-sidebar-mobile"', false)
        ->assertSee('x-persist="member-top-navbar"', false)
        ->getContent();

    expect($html)
        ->toContain('wire:navigate')
        ->toContain(route('member.dashboard', absolute: false))
        ->toContain(route('member.genealogy', absolute: false))
        ->toContain(route('member.settings.profile', absolute: false));

    expect($html)->not->toMatch('/href="[^"]*admin\/dashboard[^"]*"[^>]*wire:navigate/');
});

test('member settings pages keep spa links between related screens', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('member.settings.profile'))
        ->assertOk()
        ->assertSee('wire:navigate', false)
        ->assertSee('Profile Settings')
        ->assertSee('Theme preference');
});

test('every sidebar destination has a registered route', function () {
    foreach (MemberSidebarMenu::memberDestinations() as $destination) {
        expect(\Illuminate\Support\Facades\Route::has($destination['route']))
            ->toBeTrue("Missing route for {$destination['label']}");
    }

    foreach (MemberSidebarMenu::adminItems() as $destination) {
        expect(\Illuminate\Support\Facades\Route::has($destination['route']))
            ->toBeTrue("Missing route for {$destination['label']}");
    }
});

test('every member sidebar link opens its corresponding page', function (string $label, string $routeName, string $title) {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route($routeName))
        ->assertOk()
        ->assertSee('<title>'.$title, false);
})->with(collect(MemberSidebarMenu::memberDestinations())->mapWithKeys(
    fn (array $destination) => [$destination['label'] => [$destination['label'], $destination['route'], $destination['title']]]
)->all());

test('member sidebar html points to every member destination and has no dead hashes', function () {
    $user = User::factory()->create();

    $html = $this->actingAs($user)
        ->get(route('member.dashboard'))
        ->assertOk()
        ->getContent();

    foreach (MemberSidebarMenu::memberDestinations() as $destination) {
        expect($html)->toContain(route($destination['route'], absolute: false));
        expect($html)->toContain($destination['label']);
    }

    expect($html)->not->toContain('href="#"');
});

test('admin sidebar links open their corresponding admin pages', function () {
    Role::findOrCreate('admin');
    Role::findOrCreate('super-admin');

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin);

    $html = $this->get(route('member.dashboard'))
        ->assertOk()
        ->getContent();

    foreach (MemberSidebarMenu::adminItems() as $destination) {
        expect($html)->toContain($destination['label']);
        expect($html)->toContain(route($destination['route'], absolute: false));

        $this->get(route($destination['route']))
            ->assertOk()
            ->assertSee('<title>'.$destination['title'], false);
    }

    expect($html)->not->toContain('href="#"');
});

test('member sidebar links stay on the current host when APP_URL differs', function () {
    $user = User::factory()->create();

    $html = $this->actingAs($user)
        ->withServerVariables(['HTTP_HOST' => 'localhost:8000'])
        ->get('/member/dashboard')
        ->assertOk()
        ->getContent();

    expect($html)
        ->toContain('href="'.route('member.genealogy', absolute: false).'"')
        ->and($html)->not->toContain('http://wla.test'.route('member.genealogy', absolute: false));
});

test('genealogy explorer opens for authenticated members', function () {
    $user = User::factory()->unverified()->create([
        'username' => 'tree.owner',
    ]);

    $this->actingAs($user)
        ->get('/member/genealogy')
        ->assertOk()
        ->assertSee('Sponsorship Tree')
        ->assertSee('Sponsorship Explorer')
        ->assertSee('tree.owner');
});

test('genealogy explorer opens for a self-sponsored member', function () {
    $user = User::factory()->create([
        'username' => 'loop.root',
    ]);
    $user->forceFill(['sponsor_id' => $user->id])->save();

    User::factory()->create([
        'username' => 'loop.child',
        'sponsor_id' => $user->id,
    ]);

    $this->actingAs($user)
        ->get('/member/genealogy')
        ->assertOk()
        ->assertSee('loop.root')
        ->assertSee('loop.child')
        ->assertSee('Sponsorship Explorer');
});
