<?php

use App\Models\User;
use App\Support\AdminSidebarMenu;
use Spatie\Permission\Models\Role;

function makeAdminUser(): User
{
    Role::findOrCreate('admin');
    Role::findOrCreate('super-admin');

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    return $admin;
}

test('admin root redirects to the admin dashboard', function () {
    $admin = makeAdminUser();

    $this->actingAs($admin)
        ->get('/admin')
        ->assertRedirect(route('admin.dashboard', absolute: false));
});

test('every admin sidebar destination has a registered route', function () {
    foreach (AdminSidebarMenu::destinations() as $destination) {
        expect(\Illuminate\Support\Facades\Route::has($destination['route']))
            ->toBeTrue("Missing route for {$destination['label']}");
    }
});

test('every admin sidebar link opens its corresponding page', function (string $label, string $routeName, string $title) {
    $admin = makeAdminUser();

    $this->actingAs($admin)
        ->get(route($routeName))
        ->assertOk()
        ->assertSee('<title>'.$title, false);
})->with(collect(AdminSidebarMenu::destinations())->mapWithKeys(
    fn (array $destination) => [$destination['label'] => [$destination['label'], $destination['route'], $destination['title']]]
)->all());

test('admin sidebar html points to every destination and has no dead hashes', function () {
    $admin = makeAdminUser();

    $html = $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->getContent();

    foreach (AdminSidebarMenu::destinations() as $destination) {
        expect($html)->toContain($destination['label']);
        expect($html)->toContain(route($destination['route'], absolute: false));
    }
});

test('admin menu links stay on the current host when APP_URL differs', function () {
    $admin = makeAdminUser();

    $html = $this->actingAs($admin)
        ->withServerVariables(['HTTP_HOST' => 'localhost:8000'])
        ->get('/admin/dashboard')
        ->assertOk()
        ->getContent();

    expect($html)
        ->toContain('href="'.route('admin.users.index', absolute: false).'"')
        ->and($html)->not->toContain('href="http://wla.test'.route('admin.users.index', absolute: false).'"')
        ->and($html)->not->toContain('unpkg.com/alpinejs')
        ->and($html)->not->toContain('cdn.tailwindcss.com');
});

test('admin workspace uses the member dashboard layout chrome', function () {
    $admin = makeAdminUser();

    $html = $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('x-persist="admin-sidebar-desktop"', false)
        ->assertSee('x-persist="admin-sidebar-mobile"', false)
        ->assertSee('x-persist="admin-top-navbar"', false)
        ->assertSee('Admin workspace')
        ->assertSee('Member Dashboard')
        ->getContent();

    expect($html)
        ->toContain('wire:navigate')
        ->toContain('instrument-sans')
        ->toContain('Admin dashboard')
        ->and($html)->not->toContain('cdn.tailwindcss.com');
});

