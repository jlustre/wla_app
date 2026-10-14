<?php

use App\Models\Company;
use App\Models\MemberCompanyAffiliation;
use App\Models\User;

test('member sidebar only lists active companies', function () {
    $active = Company::create(['name' => 'Active Co', 'slug' => 'active-co', 'status' => '1']);
    Company::create(['name' => 'Inactive Co', 'slug' => 'inactive-co', 'status' => '0']);

    $user = User::factory()->create();

    $html = $this->actingAs($user)
        ->get(route('member.dashboard'))
        ->assertOk()
        ->getContent();

    expect($html)
        ->toContain('Active Co')
        ->toContain(route('company.dashboard', $active, absolute: false))
        ->not->toContain('Inactive Co');
});

test('company dashboard renders all required tabs and an invitation link', function () {
    $company = Company::create(['name' => 'Acme Wellness', 'slug' => 'acme-wellness', 'status' => '1']);
    $user = User::factory()->create();

    $html = $this->actingAs($user)
        ->get(route('company.dashboard', $company))
        ->assertOk()
        ->getContent();

    expect($html)
        ->toContain('Company Profile')
        ->toContain('Products')
        ->toContain('Compensation Plan')
        ->toContain('Resources')
        ->toContain('Invitation')
        ->toContain('register?ref=')
        ->toContain('company=acme-wellness');
});

test('company dashboard reflects an active affiliation as joined', function () {
    $company = Company::create(['name' => 'Joined Co', 'slug' => 'joined-co', 'status' => '1']);
    $user = User::factory()->create();

    MemberCompanyAffiliation::create([
        'user_id' => $user->id,
        'company_id' => $company->id,
        'status' => 'active',
        'joined_at' => now(),
    ]);

    $this->actingAs($user)
        ->get(route('company.dashboard', $company))
        ->assertOk()
        ->assertSee('Joined')
        ->assertDontSee('Not Joined');
});
