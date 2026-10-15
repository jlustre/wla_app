<?php

test('app service provider caps mysql utf8mb4 string indexes', function () {
    $provider = file_get_contents(dirname(__DIR__, 2).'/app/Providers/AppServiceProvider.php');

    expect($provider)->toContain('Schema::defaultStringLength(191)');
});

test('password reset tokens use a short enough email primary key', function () {
    $migration = file_get_contents(dirname(__DIR__, 2).'/database/migrations/0001_01_01_000000_create_users_table.php');

    expect($migration)
        ->toContain("string('email', 191)->primary()")
        ->toContain("string('email', 191)->unique()")
        ->toContain("string('id', 191)->primary()");
});
