<?php

return [
    'root_sponsor_username' => $_ENV['WLA_ROOT_SPONSOR_USERNAME'] ?? 'system',
    'allow_root_registrations' => false,
    'default_company_fallback_mode' => 'system_account',
    'require_email_verification' => true,
    'seed_demo_data' => env('SEED_DEMO_DATA', false),
];