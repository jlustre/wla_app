<?php

use App\Livewire\Admin\Company\Onboarding;
use App\Models\Company;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

test('onboarding wizard creates a company with every field filled', function () {
    Role::findOrCreate('admin');
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $this->actingAs($admin);

    Livewire::test(Onboarding::class)
        ->set('name', 'Acme Wellness')
        ->set('slug', 'acme-wellness')
        ->set('category', 'Health')
        ->set('status', '1')
        ->set('tagline', 'Feel better, live better')
        ->set('ceo_name', 'Jane CEO')
        ->set('short_description', 'Short desc')
        ->set('full_description', 'Full desc')
        ->set('primary_color', '#111111')
        ->set('secondary_color', '#222222')
        ->set('highlight_color', '#333333')
        ->set('background_color', '#444444')
        ->set('style_meta_json', '{"main_products":"Shakes","compensation":"Binary"}')
        ->set('website_link', 'https://acme.test')
        ->set('signup_link', 'https://acme.test/join')
        ->set('comp_plan_link', 'https://acme.test/comp')
        ->set('backoffice_link', 'https://acme.test/backoffice')
        ->set('intro_video_link', 'https://acme.test/video')
        ->set('webinar_link', 'https://acme.test/webinar')
        ->set('phone', '+1 555 0100')
        ->set('location', 'Austin, TX')
        ->set('logo', \Illuminate\Http\UploadedFile::fake()->image('logo.png'))
        ->call('save');

    $company = Company::where('slug', 'acme-wellness')->firstOrFail();

    expect($company->name)->toBe('Acme Wellness');
    expect($company->category)->toBe('Health');
    expect($company->status)->toBe('1');
    expect($company->tagline)->toBe('Feel better, live better');
    expect($company->ceo_name)->toBe('Jane CEO');
    expect($company->website_link)->toBe('https://acme.test');
    expect($company->signup_link)->toBe('https://acme.test/join');
    expect($company->comp_plan_link)->toBe('https://acme.test/comp');
    expect($company->backoffice_link)->toBe('https://acme.test/backoffice');
    expect($company->intro_video_link)->toBe('https://acme.test/video');
    expect($company->webinar_link)->toBe('https://acme.test/webinar');
    expect($company->phone)->toBe('+1 555 0100');
    expect($company->location)->toBe('Austin, TX');
    expect($company->primary_color)->toBe('#111111');
    expect($company->style_meta)->toBe(['main_products' => 'Shakes', 'compensation' => 'Binary']);
    expect($company->logo)->not->toBeNull();
});

test('onboarding wizard edits an existing company without re-uploading the logo', function () {
    Role::findOrCreate('admin');
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $this->actingAs($admin);

    $company = Company::create([
        'name' => 'Old Name',
        'slug' => 'old-slug',
        'logo' => 'logos/existing.png',
        'category' => 'Legal',
        'status' => '1',
        'short_description' => 'old',
        'website_link' => 'https://old.test',
        'signup_link' => 'https://old.test/join',
        'comp_plan_link' => 'https://old.test/comp',
    ]);

    Livewire::test(Onboarding::class, ['companyId' => $company->id])
        ->assertSet('name', 'Old Name')
        ->assertSet('existingLogo', 'logos/existing.png')
        ->set('name', 'New Name')
        ->set('status', '0')
        ->set('tagline', 'Updated tagline')
        ->call('save');

    $company->refresh();

    expect($company->name)->toBe('New Name');
    expect($company->status)->toBe('0');
    expect($company->tagline)->toBe('Updated tagline');
    expect($company->logo)->toBe('logos/existing.png');
});

test('admin company create and edit pages render over http', function () {
    Role::findOrCreate('admin');
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $company = Company::create([
        'name' => 'HTTP Co',
        'slug' => 'http-co',
        'status' => '1',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.companies.index'))
        ->assertOk()
        ->assertSee('HTTP Co')
        ->assertSee('Draft');

    $this->actingAs($admin)
        ->get(route('admin.companies.create'))
        ->assertOk()
        ->assertSee('Add New Affiliated Company');

    $this->actingAs($admin)
        ->get(route('admin.companies.edit', $company))
        ->assertOk()
        ->assertSee('Edit HTTP Co')
        ->assertSee('Manage Products / Compensation / Resources');
});
