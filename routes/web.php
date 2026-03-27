<?php

use Illuminate\Support\Facades\Route;

@include_once __DIR__.'/auth.php';
@include_once __DIR__.'/admin.php';
@include_once __DIR__.'/member.php';

Route::get('/', function () {
    return view('welcome');
});
