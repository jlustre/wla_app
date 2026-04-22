<div class="card">
    <div class="max-w-6xl flex md:flex-row gap-6 mx-auto py-8 px-2 md:px-0">
        <!-- Profile Card -->
        <div
            class="bg-white rounded-3xl shadow-xl p-6 flex flex-col md:flex-row items-center md:items-end gap-6 relative mb-8">
            <div
                class="absolute inset-x-0 top-0 h-24 rounded-t-3xl bg-gradient-to-r from-emerald-500 via-cyan-500 to-blue-500">
            </div>
            <div class="relative z-10 mt-10 md:mt-0">
                <img src="{{ $profile['avatar'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($profile['name']) }}"
                    alt="Profile" class="h-28 w-28 rounded-2xl border-4 border-white object-cover shadow-lg bg-white">
            </div>
            <div class="flex-1 z-10">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $profile['name'] }}</h2>
                        <p class="text-gray-500">{{ $profile['email'] }}</p>
                        <span
                            class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-200 mt-2">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            Active Member
                        </span>
                    </div>
                    <button
                        class="mt-2 md:mt-0 px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-xl text-sm font-semibold text-gray-700 shadow border border-gray-200">Edit
                        Profile</button>
                </div>
            </div>
            <!-- Referral Information -->
            <div class="bg-white rounded-2xl shadow p-5 flex flex-col gap-3">
                <span class="font-semibold text-gray-700">Referral Information</span>
                <div class="flex items-center gap-2">
                    <input type="text" readonly value="{{ $profile['referral_link'] ?? 'https://yourapp.com/ref/xyz' }}"
                        class="flex-1 bg-gray-100 rounded px-2 py-1 text-xs">
                    <button class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-semibold">Copy
                        Link</button>
                </div>
                <div class="flex items-center gap-2 mt-2">
                    <img src="https://ui-avatars.com/api/?name=James+Thompson" class="w-8 h-8 rounded-full" alt="">
                    <div>
                        <span class="text-xs font-semibold text-gray-700">James Thompson</span>
                        <div class="text-xs text-gray-400">Joined on Sept 15, 2023</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-6 w-full">
            <!-- Stats Card Right -->
            <div class="flex flex-row md:flex-col gap-4 md:gap-4 w-full md:w-56">
                <div class="bg-white rounded-2xl shadow p-4 flex flex-col items-center flex-1">
                    <span class="text-2xl font-bold text-emerald-600">{{ $profile['total_referrals'] ?? 0 }}</span>
                    <span class="text-xs text-gray-500 mt-1">Total Referrals</span>
                </div>
                <div class="bg-white rounded-2xl shadow p-4 flex flex-col items-center flex-1">
                    <span class="text-2xl font-bold text-blue-600">{{ $profile['companies_joined'] ?? 0 }}</span>
                    <span class="text-xs text-gray-500 mt-1">Companies Joined</span>
                </div>
                <div class="bg-white rounded-2xl shadow p-4 flex flex-col items-center flex-1">
                    <span class="text-2xl font-bold text-indigo-600">{{ $profile['team_size'] ?? 0 }}</span>
                    <span class="text-xs text-gray-500 mt-1">Team Size</span>
                </div>
                <div class="bg-white rounded-2xl shadow p-4 flex flex-col items-center flex-1">
                    <span class="text-2xl font-bold text-indigo-600">{{ $profile['total_prospects'] ?? 0 }}</span>
                    <span class="text-xs text-gray-500 mt-1">Total Prospects</span>
                </div>
            </div>
            <!-- Tabs Navigation & Placeholders -->
            <div class="w-full flex flex-col mb-4" x-data="{ tab: 'profile-info' }">
                <div class="flex space-x-2 md:space-x-4 border-b border-gray-200 mb-4">
                    <button
                        class="px-4 py-2 text-sm font-semibold text-gray-700 border-b-2 border-transparent hover:border-emerald-500 focus:outline-none focus:border-emerald-500 transition"
                        :class="{ 'border-emerald-500 text-emerald-700': tab === 'profile-info' }"
                        @click="tab = 'profile-info'">Profile Info</button>
                    <button
                        class="px-4 py-2 text-sm font-semibold text-gray-700 border-b-2 border-transparent hover:border-blue-500 focus:outline-none focus:border-blue-500 transition"
                        :class="{ 'border-blue-500 text-blue-700': tab === 'business-info' }"
                        @click="tab = 'business-info'">Business Info</button>
                    <button
                        class="px-4 py-2 text-sm font-semibold text-gray-700 border-b-2 border-transparent hover:border-indigo-500 focus:outline-none focus:border-indigo-500 transition"
                        :class="{ 'border-indigo-500 text-indigo-700': tab === 'password-change' }"
                        @click="tab = 'password-change'">Password Change</button>
                </div>
                <!-- Tab Placeholders -->
                <div x-show="tab === 'profile-info'" class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-4">
                    <span class="text-gray-700 text-sm">Profile Info content goes here.</span>
                </div>
                <div x-show="tab === 'business-info'" class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-4">
                    <span class="text-gray-700 text-sm">Business Info content goes here.</span>
                </div>
                <div x-show="tab === 'password-change'" class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-4">
                    <span class="text-gray-700 text-sm">Password Change content goes here.</span>
                </div>
            </div>

            <!-- Companies Participation -->
            <div class="bg-white rounded-2xl shadow p-5 flex flex-col gap-3">
                <span class="font-semibold text-gray-700">MLM Participation</span>
                <div class="flex flex-col gap-2">
                    <div class="flex items-center justify-between">
                        <span>Amway</span>
                        <span
                            class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded text-xs font-semibold">Joined</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Herbalife</span>
                        <span
                            class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded text-xs font-semibold">Joined</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>NU SKIN</span>
                        <span class="px-2 py-0.5 bg-gray-100 text-gray-500 rounded text-xs font-semibold">Not
                            Joined</span>
                    </div>
                </div>
            </div>
            <!-- Team Summary -->
            <div class="bg-white rounded-2xl shadow p-5 flex flex-col gap-3">
                <span class="font-semibold text-gray-700">Team Summary</span>
                <div class="flex items-center gap-6">
                    <div>
                        <span class="text-lg font-bold text-emerald-600">24</span>
                        <span class="block text-xs text-gray-500">Direct Referrals</span>
                    </div>
                    <div>
                        <span class="text-lg font-bold text-indigo-600">240</span>
                        <span class="block text-xs text-gray-500">Total Team Size</span>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="text-xs font-semibold text-gray-700">Recent Joins</span>
                    <div class="flex items-center gap-2 mt-1">
                        <img src="https://ui-avatars.com/api/?name=Mark+Spencer" class="w-7 h-7 rounded-full" alt="">
                        <img src="https://ui-avatars.com/api/?name=Sarah+Lin" class="w-7 h-7 rounded-full" alt="">
                        <img src="https://ui-avatars.com/api/?name=Alex+Wong" class="w-7 h-7 rounded-full" alt="">
                        <span class="text-xs text-gray-400">+21</span>
                    </div>
                </div>
            </div>

            <!-- Prospect Tracker -->
            <div class="bg-white rounded-2xl shadow p-5 flex flex-col gap-3 md:col-span-3">
                <span class="font-semibold text-gray-700">Prospect Tracker</span>
                <table class="w-full text-xs">
                    <thead>
                        <tr class="text-gray-500">
                            <th class="text-left py-1">Name</th>
                            <th class="text-left py-1">Status</th>
                            <th class="text-left py-1">Last Contact</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-1">Tom Harris</td>
                            <td><span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded">Pinged</span>
                            </td>
                            <td>2d ago</td>
                        </tr>
                        <tr>
                            <td class="py-1">Lisa Graham</td>
                            <td><span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded">Interested</span>
                            </td>
                            <td>3d ago</td>
                        </tr>
                        <tr>
                            <td class="py-1">David Lee</td>
                            <td><span class="px-2 py-0.5 bg-green-100 text-green-700 rounded">Followed
                                    Up</span></td>
                            <td>5d ago</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Activity -->
            <div class="bg-white rounded-2xl shadow p-5 flex flex-col gap-3 md:col-span-2">
                <span class="font-semibold text-gray-700">Activity</span>
                <ul class="divide-y divide-gray-100">
                    <li class="py-2 flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        <span class="text-xs text-gray-700">Alex Wong joined Herbalife</span>
                        <span class="ml-auto text-xs text-gray-400">2h ago</span>
                    </li>
                    <li class="py-2 flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-yellow-400"></span>
                        <span class="text-xs text-gray-700">Missed potential spillover</span>
                        <span class="ml-auto text-xs text-gray-400">4h ago</span>
                    </li>
                    <li class="py-2 flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-blue-400"></span>
                        <span class="text-xs text-gray-700">Sarah Lin invited a new member</span>
                        <span class="ml-auto text-xs text-gray-400">6h ago</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>