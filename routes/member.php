<?php

use App\Http\Controllers\MemberContentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
	Route::view('member/genealogy', 'member.genealogy')->name('member.genealogy');
	Route::get('api/genealogy/tree', [\App\Http\Controllers\MemberHierarchyController::class, 'sponsorshipTree']);

	Route::prefix('member')->name('member.')->group(function () {
		Route::get('sponsor', [MemberContentController::class, 'page'])->defaults('page', 'sponsor')->name('sponsor');
		Route::get('invite-link', [MemberContentController::class, 'page'])->defaults('page', 'invite-link')->name('invite-link');
		Route::get('sponsored-members', [MemberContentController::class, 'page'])->defaults('page', 'sponsored-members')->name('sponsored-members');
		Route::get('presentations', [MemberContentController::class, 'page'])->defaults('page', 'presentations')->name('presentations');
		Route::get('watch-video', [MemberContentController::class, 'page'])->defaults('page', 'watch-video')->name('watch-video');
		Route::get('resources', [MemberContentController::class, 'page'])->defaults('page', 'resources')->name('resources');
		Route::get('events', [MemberContentController::class, 'page'])->defaults('page', 'events')->name('events');
		Route::get('notifications', [MemberContentController::class, 'page'])->defaults('page', 'notifications')->name('notifications');
		Route::get('support', [MemberContentController::class, 'page'])->defaults('page', 'support')->name('support');
		Route::get('settings', [MemberContentController::class, 'page'])->defaults('page', 'settings')->name('settings');

		Route::prefix('settings')->name('settings.')->group(function () {
			Route::get('profile', [MemberContentController::class, 'setting'])->defaults('setting', 'profile')->name('profile');
			Route::get('security', [MemberContentController::class, 'setting'])->defaults('setting', 'security')->name('security');
			Route::get('password', [MemberContentController::class, 'setting'])->defaults('setting', 'password')->name('password');
			Route::get('notifications', [MemberContentController::class, 'setting'])->defaults('setting', 'notifications')->name('notifications');
			Route::get('privacy', [MemberContentController::class, 'setting'])->defaults('setting', 'privacy')->name('privacy');
			Route::get('theme', [MemberContentController::class, 'setting'])->defaults('setting', 'theme')->name('theme');
		});
	});
});
