@extends('layouts.member')

@section('content')
<div class="min-h-screen w-full bg-gradient-to-br from-indigo-50 via-white to-indigo-100 py-10">
    <div class="max-w-5xl mx-auto flex flex-col gap-8 items-center">
        <h2 class="font-bold text-3xl text-gray-900 tracking-tight mb-2">{{ __('Profile') }}</h2>
        <div class="w-full flex flex-col md:flex-row gap-10 md:gap-16 items-start justify-center"
            x-data="{ tab: 'info' }">
            <!-- Left Column: Avatar & Quick Info Modern Card -->
            <div
                class="w-full md:w-80 bg-white/70 backdrop-blur-md shadow-2xl rounded-3xl flex flex-col items-center py-10 px-8 border border-indigo-100 mb-8 md:mb-0">
                <div class="relative mb-7">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->username) }}&background=4F46E5&color=fff&size=180"
                        class="w-36 h-36 rounded-full border-4 border-indigo-400 shadow-lg object-cover" alt="Avatar">
                </div>
                <div class="w-full bg-white/80 border border-indigo-100 rounded-2xl p-6 flex flex-col gap-4 shadow-sm">
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500">Username</span>
                        <span class="font-semibold text-lg text-gray-900">{{ auth()->user()->username }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500">Email</span>
                        <span class="text-gray-700">{{ auth()->user()->email }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500">Profile Created</span>
                        <span class="text-gray-700">{{ auth()->user()->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500">Is Logged In</span>
                        <span
                            class="px-2 py-0.5 rounded text-xs font-semibold {{ auth()->check() ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{
                            auth()->check() ? 'Yes' : 'No' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500">Is Active</span>
                        <span
                            class="px-2 py-0.5 rounded text-xs font-semibold {{ auth()->user()->active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{
                            auth()->user()->active ? 'Yes' : 'No' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500">Last Login</span>
                        <span class="text-gray-700">{{ auth()->user()->last_login_at ?
                            auth()->user()->last_login_at->format('M d, Y H:i') : 'N/A' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500">Last IP</span>
                        <span class="text-gray-700">{{ auth()->user()->last_login_ip ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Right Column: Modern Card with Tabs -->
            <div class="flex-1 w-full max-w-2xl mx-auto">
                <div class="mb-6 flex gap-2">
                    <button
                        :class="tab === 'info' ? 'bg-indigo-600 text-white shadow' : 'bg-white/80 text-indigo-700 border border-indigo-200 hover:bg-indigo-50'"
                        class="px-5 py-2 rounded-full font-semibold text-sm transition focus:outline-none"
                        @click="tab = 'info'">Profile Info</button>
                    <button
                        :class="tab === 'password' ? 'bg-indigo-600 text-white shadow' : 'bg-white/80 text-indigo-700 border border-indigo-200 hover:bg-indigo-50'"
                        class="px-5 py-2 rounded-full font-semibold text-sm transition focus:outline-none"
                        @click="tab = 'password'">Change Password</button>
                    <button
                        :class="tab === 'delete' ? 'bg-indigo-600 text-white shadow' : 'bg-white/80 text-indigo-700 border border-indigo-200 hover:bg-indigo-50'"
                        class="px-5 py-2 rounded-full font-semibold text-sm transition focus:outline-none"
                        @click="tab = 'delete'">Delete Account</button>
                </div>
                <div class="p-6 bg-white/90 shadow-xl rounded-2xl">
                    <div class="max-w-xl mx-auto">
                        <div x-show="tab === 'info'">
                            <livewire:profile.update-profile-information-form />
                        </div>
                        <div x-show="tab === 'password'">
                            <livewire:profile.update-password-form />
                        </div>
                        <div x-show="tab === 'delete'">
                            <livewire:profile.delete-user-form />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection