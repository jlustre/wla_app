@extends('layouts.member')

@section('title', 'Kanban Command Center')

@section('content')
<div class="min-h-screen bg-slate-50 text-slate-900">
    <div class="mx-auto max-w-[1800px] px-4 py-6 sm:px-6 lg:px-8">
        <!-- Context / Scope Bar -->
        <div class="mb-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-600">WLA Funnel Workspace</p>
                    <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Funnel Pipeline + Kanban Command Center</h1>
                    <p class="mt-1 text-sm text-slate-500">
                        Manage prospect flow across your full recruiting pipeline from
                        <span class="font-semibold text-slate-700">Added</span> to
                        <span class="font-semibold text-slate-700">Closed</span>.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700 ring-1 ring-inset ring-indigo-200">
                        Viewing: Joey Lustre’s Pipeline
                    </span>
                    <span class="inline-flex items-center rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700 ring-1 ring-inset ring-sky-200">
                        Team Scope: Direct + Downline
                    </span>
                    <span class="inline-flex items-center rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-200">
                        Admin Mode Enabled
                    </span>
                </div>
            </div>

            <!-- Command Header -->
            <div class="grid gap-4 px-5 py-4 xl:grid-cols-[1.6fr_1fr]">
                <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    <label class="block">
                        <span class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Search</span>
                        <div class="flex items-center rounded-xl border border-slate-200 bg-slate-50 px-3">
                            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.032 4.89l3.539 3.54a.75.75 0 1 1-1.06 1.06l-3.54-3.539A7 7 0 0 1 2 9Z" clip-rule="evenodd"/>
                            </svg>
                            <input type="text" placeholder="Search prospects..." class="w-full border-0 bg-transparent px-2 py-2.5 text-sm focus:outline-none focus:ring-0" />
                        </div>
                    </label>

                    <label class="block">
                        <span class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Date Range</span>
                        <select class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                            <option>Last 30 Days</option>
                            <option>Last 7 Days</option>
                            <option>This Month</option>
                            <option>Custom Range</option>
                        </select>
                    </label>

                    <label class="block">
                        <span class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Source</span>
                        <select class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                            <option>All Sources</option>
                            <option>Referral</option>
                            <option>Facebook</option>
                            <option>Webinar</option>
                            <option>Personal Contact</option>
                        </select>
                    </label>

                    <label class="block">
                        <span class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Company / Opportunity</span>
                        <select class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                            <option>All Opportunities</option>
                            <option>WLA Membership</option>
                            <option>Experior</option>
                            <option>Hydrogen Water</option>
                            <option>Bitcoin Futures</option>
                        </select>
                    </label>
                </div>

                <div class="flex flex-col gap-3">
                    <div class="grid gap-3 sm:grid-cols-2">
                        <label class="block">
                            <span class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Assigned User</span>
                            <select class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                                <option>All Assigned Users</option>
                                <option>Joey Lustre</option>
                                <option>Maria Cruz</option>
                                <option>John Reyes</option>
                            </select>
                        </label>

                        <label class="block">
                            <span class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">View Mode</span>
                            <div class="grid grid-cols-3 rounded-xl bg-slate-100 p-1 text-xs font-semibold">
                                <button class="rounded-lg px-3 py-2 text-slate-600 transition hover:bg-white hover:text-slate-900">Funnel</button>
                                <button class="rounded-lg bg-white px-3 py-2 text-indigo-700 shadow-sm">Hybrid</button>
                                <button class="rounded-lg px-3 py-2 text-slate-600 transition hover:bg-white hover:text-slate-900">Kanban</button>
                            </div>
                        </label>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <button class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                            Add Prospect
                        </button>
                        <button class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Import Leads
                        </button>
                        <button class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Create Follow-Up Task
                        </button>
                        <button class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            View Reports
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-7">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Prospects</p>
                <div class="mt-2 flex items-end justify-between">
                    <h2 class="text-2xl font-bold text-slate-900">428</h2>
                    <span class="text-xs font-semibold text-emerald-600">+12.4%</span>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Active Pipeline</p>
                <div class="mt-2 flex items-end justify-between">
                    <h2 class="text-2xl font-bold text-slate-900">311</h2>
                    <span class="text-xs font-semibold text-emerald-600">+8.1%</span>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Joined Rate</p>
                <div class="mt-2 flex items-end justify-between">
                    <h2 class="text-2xl font-bold text-slate-900">19.6%</h2>
                    <span class="text-xs font-semibold text-emerald-600">+2.3%</span>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Avg. Days to Join</p>
                <div class="mt-2 flex items-end justify-between">
                    <h2 class="text-2xl font-bold text-slate-900">11.4</h2>
                    <span class="text-xs font-semibold text-rose-600">+1.1d</span>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Pending Follow-Ups</p>
                <div class="mt-2 flex items-end justify-between">
                    <h2 class="text-2xl font-bold text-slate-900">34</h2>
                    <span class="text-xs font-semibold text-amber-600">Needs action</span>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Stalled Leads</p>
                <div class="mt-2 flex items-end justify-between">
                    <h2 class="text-2xl font-bold text-slate-900">21</h2>
                    <span class="text-xs font-semibold text-rose-600">Over 5 days</span>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Re-Engage Opportunities</p>
                <div class="mt-2 flex items-end justify-between">
                    <h2 class="text-2xl font-bold text-slate-900">17</h2>
                    <span class="text-xs font-semibold text-indigo-600">High value</span>
                </div>
            </div>
        </div>

        <!-- Funnel Visualization -->
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="mb-5 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Funnel Visualization</h2>
                    <p class="text-sm text-slate-500">Click any stage to filter the Kanban board and inspect drop-off performance.</p>
                </div>
                <div class="flex flex-wrap items-center gap-2 text-xs">
                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-200">Conversion Trend Improving</span>
                    <span class="inline-flex items-center rounded-full bg-amber-50 px-3 py-1 font-semibold text-amber-700 ring-1 ring-inset ring-amber-200">34 Follow-Ups Due</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <div class="grid min-w-[1200px] grid-cols-7 gap-3">
                    <!-- Added -->
                    <button class="group rounded-2xl border-2 border-indigo-200 bg-gradient-to-b from-indigo-600 to-indigo-700 p-4 text-left text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.15em] text-indigo-100">Stage 01</p>
                                <h3 class="mt-1 text-base font-bold">Added</h3>
                            </div>
                            <span class="rounded-full bg-white/15 px-2.5 py-1 text-xs font-semibold">128</span>
                        </div>
                        <div class="mt-5 h-2 rounded-full bg-white/15">
                            <div class="h-2 w-full rounded-full bg-white"></div>
                        </div>
                        <div class="mt-4 grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <p class="text-indigo-100">Conv. to next</p>
                                <p class="font-semibold text-white">78.1%</p>
                            </div>
                            <div>
                                <p class="text-indigo-100">Avg. days</p>
                                <p class="font-semibold text-white">1.4d</p>
                            </div>
                        </div>
                    </button>

                    <!-- Contacted -->
                    <button class="group rounded-2xl border border-slate-200 bg-gradient-to-b from-sky-500 to-sky-600 p-4 text-left text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.15em] text-sky-100">Stage 02</p>
                                <h3 class="mt-1 text-base font-bold">Contacted</h3>
                            </div>
                            <span class="rounded-full bg-white/15 px-2.5 py-1 text-xs font-semibold">100</span>
                        </div>
                        <div class="mt-5 h-2 rounded-full bg-white/15">
                            <div class="h-2 w-[78%] rounded-full bg-white"></div>
                        </div>
                        <div class="mt-4 grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <p class="text-sky-100">Conv. to next</p>
                                <p class="font-semibold text-white">67.0%</p>
                            </div>
                            <div>
                                <p class="text-sky-100">Drop-off</p>
                                <p class="font-semibold text-white">33.0%</p>
                            </div>
                        </div>
                    </button>

                    <!-- Invited -->
                    <button class="group rounded-2xl border border-slate-200 bg-gradient-to-b from-cyan-500 to-cyan-600 p-4 text-left text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.15em] text-cyan-100">Stage 03</p>
                                <h3 class="mt-1 text-base font-bold">Invited</h3>
                            </div>
                            <span class="rounded-full bg-white/15 px-2.5 py-1 text-xs font-semibold">67</span>
                        </div>
                        <div class="mt-5 h-2 rounded-full bg-white/15">
                            <div class="h-2 w-[52%] rounded-full bg-white"></div>
                        </div>
                        <div class="mt-4 grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <p class="text-cyan-100">Conv. to next</p>
                                <p class="font-semibold text-white">55.2%</p>
                            </div>
                            <div>
                                <p class="text-cyan-100">Overdue</p>
                                <p class="font-semibold text-white">9</p>
                            </div>
                        </div>
                    </button>

                    <!-- Presented -->
                    <button class="group rounded-2xl border border-slate-200 bg-gradient-to-b from-blue-500 to-blue-600 p-4 text-left text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.15em] text-blue-100">Stage 04</p>
                                <h3 class="mt-1 text-base font-bold">Presented</h3>
                            </div>
                            <span class="rounded-full bg-white/15 px-2.5 py-1 text-xs font-semibold">37</span>
                        </div>
                        <div class="mt-5 h-2 rounded-full bg-white/15">
                            <div class="h-2 w-[29%] rounded-full bg-white"></div>
                        </div>
                        <div class="mt-4 grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <p class="text-blue-100">Conv. to next</p>
                                <p class="font-semibold text-white">73.0%</p>
                            </div>
                            <div>
                                <p class="text-blue-100">Avg. days</p>
                                <p class="font-semibold text-white">2.6d</p>
                            </div>
                        </div>
                    </button>

                    <!-- Followed Up -->
                    <button class="group rounded-2xl border border-slate-200 bg-gradient-to-b from-violet-500 to-violet-600 p-4 text-left text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.15em] text-violet-100">Stage 05</p>
                                <h3 class="mt-1 text-base font-bold">Followed Up</h3>
                            </div>
                            <span class="rounded-full bg-white/15 px-2.5 py-1 text-xs font-semibold">27</span>
                        </div>
                        <div class="mt-5 h-2 rounded-full bg-white/15">
                            <div class="h-2 w-[21%] rounded-full bg-white"></div>
                        </div>
                        <div class="mt-4 grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <p class="text-violet-100">Conv. to next</p>
                                <p class="font-semibold text-white">70.4%</p>
                            </div>
                            <div>
                                <p class="text-violet-100">Pending tasks</p>
                                <p class="font-semibold text-white">14</p>
                            </div>
                        </div>
                    </button>

                    <!-- Joined -->
                    <button class="group rounded-2xl border border-slate-200 bg-gradient-to-b from-emerald-500 to-emerald-600 p-4 text-left text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.15em] text-emerald-100">Stage 06</p>
                                <h3 class="mt-1 text-base font-bold">Joined</h3>
                            </div>
                            <span class="rounded-full bg-white/15 px-2.5 py-1 text-xs font-semibold">19</span>
                        </div>
                        <div class="mt-5 h-2 rounded-full bg-white/15">
                            <div class="h-2 w-[15%] rounded-full bg-white"></div>
                        </div>
                        <div class="mt-4 grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <p class="text-emerald-100">Join rate</p>
                                <p class="font-semibold text-white">19.0%</p>
                            </div>
                            <div>
                                <p class="text-emerald-100">Trend</p>
                                <p class="font-semibold text-white">+3.8%</p>
                            </div>
                        </div>
                    </button>

                    <!-- Closed -->
                    <button class="group rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-500 to-slate-600 p-4 text-left text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.15em] text-slate-200">Stage 07</p>
                                <h3 class="mt-1 text-base font-bold">Closed</h3>
                            </div>
                            <span class="rounded-full bg-white/15 px-2.5 py-1 text-xs font-semibold">80</span>
                        </div>
                        <div class="mt-5 h-2 rounded-full bg-white/15">
                            <div class="h-2 w-[62%] rounded-full bg-white"></div>
                        </div>
                        <div class="mt-4 grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <p class="text-slate-200">Resolved</p>
                                <p class="font-semibold text-white">80</p>
                            </div>
                            <div>
                                <p class="text-slate-200">Archived</p>
                                <p class="font-semibold text-white">58</p>
                            </div>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Hybrid Layout -->
        <div class="grid gap-6 2xl:grid-cols-[1.7fr_420px]">
            <!-- Kanban Board -->
            <div class="min-w-0">
                <div class="mb-4 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Kanban Pipeline Board</h2>
                        <p class="text-sm text-slate-500">Drag prospects between stages, manage next steps, and monitor urgency in one workspace.</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Sort
                        </button>
                        <button class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Filter
                        </button>
                        <button class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Bulk Actions
                        </button>
                    </div>
                </div>

                <!-- Bulk Action Bar -->
                <div class="mb-4 flex flex-col gap-3 rounded-2xl border border-indigo-200 bg-indigo-50 p-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 text-sm font-bold text-white">5</span>
                        <div>
                            <p class="text-sm font-semibold text-indigo-900">5 prospects selected</p>
                            <p class="text-xs text-indigo-700">Apply bulk workflow updates across your pipeline.</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button class="rounded-xl bg-white px-3 py-2 text-sm font-semibold text-slate-700 ring-1 ring-inset ring-indigo-200">Move Stage</button>
                        <button class="rounded-xl bg-white px-3 py-2 text-sm font-semibold text-slate-700 ring-1 ring-inset ring-indigo-200">Assign User</button>
                        <button class="rounded-xl bg-white px-3 py-2 text-sm font-semibold text-slate-700 ring-1 ring-inset ring-indigo-200">Add Tag</button>
                        <button class="rounded-xl bg-white px-3 py-2 text-sm font-semibold text-slate-700 ring-1 ring-inset ring-indigo-200">Send Sequence</button>
                        <button class="rounded-xl bg-white px-3 py-2 text-sm font-semibold text-slate-700 ring-1 ring-inset ring-indigo-200">Close / Archive</button>
                    </div>
                </div>

                <div class="overflow-x-auto pb-2">
                    <div class="flex min-w-[1750px] gap-4">
                        <!-- Column: Added -->
                        <div class="w-[280px] shrink-0 rounded-2xl border border-slate-200 bg-white shadow-sm">
                            <div class="rounded-t-2xl border-b border-slate-100 bg-slate-50 px-4 py-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900">Added</h3>
                                        <p class="mt-1 text-xs text-slate-500">128 prospects • 11 overdue</p>
                                    </div>
                                    <span class="rounded-full bg-indigo-100 px-2.5 py-1 text-xs font-semibold text-indigo-700">New</span>
                                </div>
                                <div class="mt-3 flex items-center gap-2 text-xs">
                                    <span class="rounded-full bg-slate-200 px-2 py-1 font-semibold text-slate-700">Conv. 78.1%</span>
                                    <span class="rounded-full bg-amber-100 px-2 py-1 font-semibold text-amber-700">11 pending</span>
                                </div>
                            </div>

                            <div class="space-y-3 p-3">
                                <!-- Card -->
                                <article class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm transition hover:border-indigo-300 hover:shadow-md">
                                    <div class="mb-3 flex items-start justify-between gap-2">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-700">MR</div>
                                            <div>
                                                <h4 class="text-sm font-semibold text-slate-900">Michael Ramos</h4>
                                                <p class="text-xs text-slate-500">Referral • WLA Membership</p>
                                            </div>
                                        </div>
                                        <input type="checkbox" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                                    </div>

                                    <div class="mb-3 flex flex-wrap gap-1.5">
                                        <span class="rounded-full bg-rose-100 px-2 py-1 text-[11px] font-semibold text-rose-700">Hot</span>
                                        <span class="rounded-full bg-amber-100 px-2 py-1 text-[11px] font-semibold text-amber-700">Follow up today</span>
                                    </div>

                                    <div class="space-y-2 text-xs text-slate-600">
                                        <div class="flex items-center justify-between">
                                            <span>Assigned</span>
                                            <span class="font-semibold text-slate-800">Joey</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span>Sponsor</span>
                                            <span class="font-semibold text-slate-800">Maria Cruz</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span>Last activity</span>
                                            <span class="font-semibold text-slate-800">2 hrs ago</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span>Next action</span>
                                            <span class="font-semibold text-amber-700">Call today</span>
                                        </div>
                                    </div>

                                    <div class="mt-3 rounded-xl bg-slate-50 p-2 text-xs text-slate-500">
                                        Wants to learn about the WLA model and passive override structure.
                                    </div>

                                    <div class="mt-3 flex items-center justify-between border-t border-slate-100 pt-3 text-xs">
                                        <div class="flex items-center gap-3 text-slate-500">
                                            <span>📝 2</span>
                                            <span>✅ 1</span>
                                            <span>🔥 86%</span>
                                        </div>
                                        <button class="font-semibold text-indigo-600">Open</button>
                                    </div>
                                </article>

                                <article class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm transition hover:border-indigo-300 hover:shadow-md">
                                    <div class="mb-3 flex items-start justify-between gap-2">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-sky-100 text-sm font-bold text-sky-700">AT</div>
                                            <div>
                                                <h4 class="text-sm font-semibold text-slate-900">Angela Tan</h4>
                                                <p class="text-xs text-slate-500">Facebook • Experior</p>
                                            </div>
                                        </div>
                                        <input type="checkbox" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                                    </div>
                                    <div class="mb-3 flex flex-wrap gap-1.5">
                                        <span class="rounded-full bg-slate-100 px-2 py-1 text-[11px] font-semibold text-slate-700">New</span>
                                    </div>
                                    <div class="space-y-2 text-xs text-slate-600">
                                        <div class="flex items-center justify-between"><span>Assigned</span><span class="font-semibold text-slate-800">Joey</span></div>
                                        <div class="flex items-center justify-between"><span>Next action</span><span class="font-semibold text-slate-800">Send intro message</span></div>
                                        <div class="flex items-center justify-between"><span>Days in stage</span><span class="font-semibold text-slate-800">1 day</span></div>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between border-t border-slate-100 pt-3 text-xs">
                                        <div class="flex items-center gap-3 text-slate-500"><span>📝 0</span><span>✅ 0</span><span>🔥 54%</span></div>
                                        <button class="font-semibold text-indigo-600">Open</button>
                                    </div>
                                </article>
                            </div>
                        </div>

                        <!-- Column: Contacted -->
                        <div class="w-[280px] shrink-0 rounded-2xl border border-slate-200 bg-white shadow-sm">
                            <div class="rounded-t-2xl border-b border-slate-100 bg-slate-50 px-4 py-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900">Contacted</h3>
                                        <p class="mt-1 text-xs text-slate-500">100 prospects • 7 overdue</p>
                                    </div>
                                    <span class="rounded-full bg-sky-100 px-2.5 py-1 text-xs font-semibold text-sky-700">Active</span>
                                </div>
                                <div class="mt-3 flex items-center gap-2 text-xs">
                                    <span class="rounded-full bg-slate-200 px-2 py-1 font-semibold text-slate-700">Conv. 67.0%</span>
                                    <span class="rounded-full bg-amber-100 px-2 py-1 font-semibold text-amber-700">7 overdue</span>
                                </div>
                            </div>

                            <div class="space-y-3 p-3">
                                <article class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm transition hover:border-sky-300 hover:shadow-md">
                                    <div class="mb-3 flex items-start justify-between gap-2">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-cyan-100 text-sm font-bold text-cyan-700">JL</div>
                                            <div>
                                                <h4 class="text-sm font-semibold text-slate-900">Jenny Lopez</h4>
                                                <p class="text-xs text-slate-500">Personal Contact • Hydrogen Water</p>
                                            </div>
                                        </div>
                                        <input type="checkbox" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                                    </div>
                                    <div class="mb-3 flex flex-wrap gap-1.5">
                                        <span class="rounded-full bg-emerald-100 px-2 py-1 text-[11px] font-semibold text-emerald-700">Responsive</span>
                                        <span class="rounded-full bg-violet-100 px-2 py-1 text-[11px] font-semibold text-violet-700">Presentation-ready</span>
                                    </div>
                                    <div class="space-y-2 text-xs text-slate-600">
                                        <div class="flex items-center justify-between"><span>Last activity</span><span class="font-semibold text-slate-800">Yesterday</span></div>
                                        <div class="flex items-center justify-between"><span>Next action</span><span class="font-semibold text-slate-800">Invite to webinar</span></div>
                                        <div class="flex items-center justify-between"><span>Confidence</span><span class="font-semibold text-emerald-700">82%</span></div>
                                    </div>
                                    <div class="mt-3 rounded-xl bg-slate-50 p-2 text-xs text-slate-500">
                                        Asked about water quality, antioxidants, and long-term family health benefits.
                                    </div>
                                    <div class="mt-3 flex items-center justify-between border-t border-slate-100 pt-3 text-xs">
                                        <div class="flex items-center gap-3 text-slate-500"><span>📝 4</span><span>✅ 2</span><span>📅 Today</span></div>
                                        <button class="font-semibold text-indigo-600">Open</button>
                                    </div>
                                </article>
                            </div>
                        </div>

                        <!-- Column: Invited -->
                        <div class="w-[280px] shrink-0 rounded-2xl border border-slate-200 bg-white shadow-sm">
                            <div class="rounded-t-2xl border-b border-slate-100 bg-slate-50 px-4 py-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900">Invited</h3>
                                        <p class="mt-1 text-xs text-slate-500">67 prospects • 9 pending response</p>
                                    </div>
                                    <span class="rounded-full bg-cyan-100 px-2.5 py-1 text-xs font-semibold text-cyan-700">Waiting</span>
                                </div>
                                <div class="mt-3 flex items-center gap-2 text-xs">
                                    <span class="rounded-full bg-slate-200 px-2 py-1 font-semibold text-slate-700">Conv. 55.2%</span>
                                    <span class="rounded-full bg-amber-100 px-2 py-1 font-semibold text-amber-700">9 no response</span>
                                </div>
                            </div>

                            <div class="space-y-3 p-3">
                                <article class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm transition hover:border-cyan-300 hover:shadow-md">
                                    <div class="mb-3 flex items-start justify-between gap-2">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-violet-100 text-sm font-bold text-violet-700">DK</div>
                                            <div>
                                                <h4 class="text-sm font-semibold text-slate-900">David Kim</h4>
                                                <p class="text-xs text-slate-500">Webinar Funnel • Bitcoin Futures</p>
                                            </div>
                                        </div>
                                        <input type="checkbox" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                                    </div>
                                    <div class="mb-3 flex flex-wrap gap-1.5">
                                        <span class="rounded-full bg-amber-100 px-2 py-1 text-[11px] font-semibold text-amber-700">Needs reminder</span>
                                        <span class="rounded-full bg-rose-100 px-2 py-1 text-[11px] font-semibold text-rose-700">Hot</span>
                                    </div>
                                    <div class="space-y-2 text-xs text-slate-600">
                                        <div class="flex items-center justify-between"><span>Invite sent</span><span class="font-semibold text-slate-800">2 days ago</span></div>
                                        <div class="flex items-center justify-between"><span>Next action</span><span class="font-semibold text-amber-700">Reminder PM today</span></div>
                                        <div class="flex items-center justify-between"><span>Engagement</span><span class="font-semibold text-slate-800">74%</span></div>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between border-t border-slate-100 pt-3 text-xs">
                                        <div class="flex items-center gap-3 text-slate-500"><span>📝 3</span><span>✅ 1</span><span>📩 Sent</span></div>
                                        <button class="font-semibold text-indigo-600">Open</button>
                                    </div>
                                </article>
                            </div>
                        </div>

                        <!-- Column: Presented -->
                        <div class="w-[280px] shrink-0 rounded-2xl border border-slate-200 bg-white shadow-sm">
                            <div class="rounded-t-2xl border-b border-slate-100 bg-slate-50 px-4 py-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900">Presented</h3>
                                        <p class="mt-1 text-xs text-slate-500">37 prospects • 5 high priority</p>
                                    </div>
                                    <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700">Critical</span>
                                </div>
                                <div class="mt-3 flex items-center gap-2 text-xs">
                                    <span class="rounded-full bg-slate-200 px-2 py-1 font-semibold text-slate-700">Conv. 73.0%</span>
                                    <span class="rounded-full bg-rose-100 px-2 py-1 font-semibold text-rose-700">Best close zone</span>
                                </div>
                            </div>

                            <div class="space-y-3 p-3">
                                <article class="rounded-2xl border border-rose-200 bg-rose-50 p-3 shadow-sm transition hover:shadow-md">
                                    <div class="mb-3 flex items-start justify-between gap-2">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-700">SC</div>
                                            <div>
                                                <h4 class="text-sm font-semibold text-slate-900">Sarah Chen</h4>
                                                <p class="text-xs text-slate-500">Referral • WLA Membership</p>
                                            </div>
                                        </div>
                                        <input type="checkbox" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                                    </div>
                                    <div class="mb-3 flex flex-wrap gap-1.5">
                                        <span class="rounded-full bg-rose-100 px-2 py-1 text-[11px] font-semibold text-rose-700">Very Hot</span>
                                        <span class="rounded-full bg-emerald-100 px-2 py-1 text-[11px] font-semibold text-emerald-700">Ready to decide</span>
                                    </div>
                                    <div class="space-y-2 text-xs text-slate-700">
                                        <div class="flex items-center justify-between"><span>Presented</span><span class="font-semibold">Today</span></div>
                                        <div class="flex items-center justify-between"><span>Next action</span><span class="font-semibold text-rose-700">Follow up in 6 hrs</span></div>
                                        <div class="flex items-center justify-between"><span>Conversion score</span><span class="font-semibold text-emerald-700">91%</span></div>
                                    </div>
                                    <div class="mt-3 rounded-xl bg-white/80 p-2 text-xs text-slate-600">
                                        Asked about sponsor tree, fear-of-loss placement, and how quickly she can start recruiting.
                                    </div>
                                    <div class="mt-3 flex items-center justify-between border-t border-rose-200 pt-3 text-xs">
                                        <div class="flex items-center gap-3 text-slate-500"><span>📝 5</span><span>✅ 3</span><span>🔥 91%</span></div>
                                        <button class="font-semibold text-indigo-600">Open</button>
                                    </div>
                                </article>
                            </div>
                        </div>

                        <!-- Column: Followed Up -->
                        <div class="w-[280px] shrink-0 rounded-2xl border border-slate-200 bg-white shadow-sm">
                            <div class="rounded-t-2xl border-b border-slate-100 bg-slate-50 px-4 py-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900">Followed Up</h3>
                                        <p class="mt-1 text-xs text-slate-500">27 prospects • 14 tasks open</p>
                                    </div>
                                    <span class="rounded-full bg-violet-100 px-2.5 py-1 text-xs font-semibold text-violet-700">Action</span>
                                </div>
                                <div class="mt-3 flex items-center gap-2 text-xs">
                                    <span class="rounded-full bg-slate-200 px-2 py-1 font-semibold text-slate-700">Conv. 70.4%</span>
                                    <span class="rounded-full bg-amber-100 px-2 py-1 font-semibold text-amber-700">14 tasks open</span>
                                </div>
                            </div>

                            <div class="space-y-3 p-3">
                                <article class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm transition hover:border-violet-300 hover:shadow-md">
                                    <div class="mb-3 flex items-start justify-between gap-2">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 text-sm font-bold text-emerald-700">RO</div>
                                            <div>
                                                <h4 class="text-sm font-semibold text-slate-900">Robert Ong</h4>
                                                <p class="text-xs text-slate-500">Personal Contact • Experior</p>
                                            </div>
                                        </div>
                                        <input type="checkbox" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                                    </div>
                                    <div class="mb-3 flex flex-wrap gap-1.5">
                                        <span class="rounded-full bg-violet-100 px-2 py-1 text-[11px] font-semibold text-violet-700">Sequence active</span>
                                        <span class="rounded-full bg-amber-100 px-2 py-1 text-[11px] font-semibold text-amber-700">Call tomorrow</span>
                                    </div>
                                    <div class="space-y-2 text-xs text-slate-600">
                                        <div class="flex items-center justify-between"><span>Last follow-up</span><span class="font-semibold text-slate-800">Yesterday</span></div>
                                        <div class="flex items-center justify-between"><span>Days in stage</span><span class="font-semibold text-slate-800">4 days</span></div>
                                        <div class="flex items-center justify-between"><span>Likelihood</span><span class="font-semibold text-emerald-700">79%</span></div>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between border-t border-slate-100 pt-3 text-xs">
                                        <div class="flex items-center gap-3 text-slate-500"><span>📝 6</span><span>✅ 4</span><span>📞 Due</span></div>
                                        <button class="font-semibold text-indigo-600">Open</button>
                                    </div>
                                </article>
                            </div>
                        </div>

                        <!-- Column: Joined -->
                        <div class="w-[280px] shrink-0 rounded-2xl border border-emerald-200 bg-emerald-50 shadow-sm">
                            <div class="rounded-t-2xl border-b border-emerald-200 bg-emerald-100/70 px-4 py-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h3 class="text-sm font-bold text-emerald-900">Joined</h3>
                                        <p class="mt-1 text-xs text-emerald-700">19 prospects • onboarding stage</p>
                                    </div>
                                    <span class="rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-emerald-700">Won</span>
                                </div>
                                <div class="mt-3 flex items-center gap-2 text-xs">
                                    <span class="rounded-full bg-white px-2 py-1 font-semibold text-emerald-700">Join rate 19.0%</span>
                                    <span class="rounded-full bg-white px-2 py-1 font-semibold text-emerald-700">Best month</span>
                                </div>
                            </div>

                            <div class="space-y-3 p-3">
                                <article class="rounded-2xl border border-emerald-200 bg-white p-3 shadow-sm">
                                    <div class="mb-3 flex items-start justify-between gap-2">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 text-sm font-bold text-emerald-700">LP</div>
                                            <div>
                                                <h4 class="text-sm font-semibold text-slate-900">Liza Perez</h4>
                                                <p class="text-xs text-slate-500">Joined via WLA • Referral</p>
                                            </div>
                                        </div>
                                        <input type="checkbox" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                                    </div>
                                    <div class="mb-3 flex flex-wrap gap-1.5">
                                        <span class="rounded-full bg-emerald-100 px-2 py-1 text-[11px] font-semibold text-emerald-700">Joined</span>
                                        <span class="rounded-full bg-sky-100 px-2 py-1 text-[11px] font-semibold text-sky-700">Onboarding pending</span>
                                    </div>
                                    <div class="space-y-2 text-xs text-slate-600">
                                        <div class="flex items-center justify-between"><span>Joined date</span><span class="font-semibold text-slate-800">Apr 20, 2026</span></div>
                                        <div class="flex items-center justify-between"><span>Next action</span><span class="font-semibold text-slate-800">Start onboarding</span></div>
                                        <div class="flex items-center justify-between"><span>Sponsor</span><span class="font-semibold text-slate-800">Joey</span></div>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between border-t border-emerald-100 pt-3 text-xs">
                                        <div class="flex items-center gap-3 text-slate-500"><span>📝 7</span><span>✅ 5</span><span>🎉 Won</span></div>
                                        <button class="font-semibold text-emerald-700">Open</button>
                                    </div>
                                </article>
                            </div>
                        </div>

                        <!-- Column: Closed -->
                        <div class="w-[280px] shrink-0 rounded-2xl border border-slate-200 bg-slate-100 shadow-sm">
                            <div class="rounded-t-2xl border-b border-slate-200 bg-slate-200/70 px-4 py-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900">Closed</h3>
                                        <p class="mt-1 text-xs text-slate-600">80 prospects • finalized or archived</p>
                                    </div>
                                    <span class="rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-slate-700">Final</span>
                                </div>
                                <div class="mt-3 flex items-center gap-2 text-xs">
                                    <span class="rounded-full bg-white px-2 py-1 font-semibold text-slate-700">80 resolved</span>
                                    <span class="rounded-full bg-white px-2 py-1 font-semibold text-slate-700">58 archived</span>
                                </div>
                            </div>

                            <div class="space-y-3 p-3">
                                <article class="rounded-2xl border border-slate-300 bg-white/80 p-3 shadow-sm">
                                    <div class="mb-3 flex items-start justify-between gap-2">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-200 text-sm font-bold text-slate-700">JM</div>
                                            <div>
                                                <h4 class="text-sm font-semibold text-slate-900">Jason Moore</h4>
                                                <p class="text-xs text-slate-500">Facebook • Disqualified</p>
                                            </div>
                                        </div>
                                        <input type="checkbox" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                                    </div>
                                    <div class="mb-3 flex flex-wrap gap-1.5">
                                        <span class="rounded-full bg-slate-200 px-2 py-1 text-[11px] font-semibold text-slate-700">Closed</span>
                                        <span class="rounded-full bg-rose-100 px-2 py-1 text-[11px] font-semibold text-rose-700">Not interested</span>
                                    </div>
                                    <div class="space-y-2 text-xs text-slate-600">
                                        <div class="flex items-center justify-between"><span>Closed date</span><span class="font-semibold text-slate-800">Apr 18, 2026</span></div>
                                        <div class="flex items-center justify-between"><span>Reason</span><span class="font-semibold text-slate-800">No timing</span></div>
                                        <div class="flex items-center justify-between"><span>Re-engage</span><span class="font-semibold text-indigo-700">In 60 days</span></div>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between border-t border-slate-200 pt-3 text-xs">
                                        <div class="flex items-center gap-3 text-slate-500"><span>📝 2</span><span>✅ 1</span><span>🗂 Archive</span></div>
                                        <button class="font-semibold text-slate-700">Open</button>
                                    </div>
                                </article>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side Panel -->
            <aside class="space-y-6">
                <!-- Prospect Detail Panel -->
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 bg-gradient-to-r from-indigo-600 to-sky-600 px-5 py-5 text-white">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-100">Prospect Detail</p>
                        <div class="mt-3 flex items-center gap-3">
                            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-white/15 text-lg font-bold">SC</div>
                            <div>
                                <h3 class="text-lg font-bold">Sarah Chen</h3>
                                <p class="text-sm text-indigo-100">Presented • Referral • WLA Membership</p>
                            </div>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <span class="rounded-full bg-white/15 px-3 py-1 text-xs font-semibold">Hot Lead</span>
                            <span class="rounded-full bg-white/15 px-3 py-1 text-xs font-semibold">91% Score</span>
                            <span class="rounded-full bg-white/15 px-3 py-1 text-xs font-semibold">Follow up today</span>
                        </div>
                    </div>

                    <div class="space-y-5 p-5">
                        <div>
                            <h4 class="text-xs font-semibold uppercase tracking-wide text-slate-500">Profile</h4>
                            <dl class="mt-3 space-y-3 text-sm">
                                <div class="flex justify-between gap-4">
                                    <dt class="text-slate-500">Email</dt>
                                    <dd class="font-medium text-slate-900">sarah.chen@example.com</dd>
                                </div>
                                <div class="flex justify-between gap-4">
                                    <dt class="text-slate-500">Phone</dt>
                                    <dd class="font-medium text-slate-900">(510) 555-0192</dd>
                                </div>
                                <div class="flex justify-between gap-4">
                                    <dt class="text-slate-500">Assigned User</dt>
                                    <dd class="font-medium text-slate-900">Joey Lustre</dd>
                                </div>
                                <div class="flex justify-between gap-4">
                                    <dt class="text-slate-500">Sponsor</dt>
                                    <dd class="font-medium text-slate-900">Maria Cruz</dd>
                                </div>
                                <div class="flex justify-between gap-4">
                                    <dt class="text-slate-500">Next Action</dt>
                                    <dd class="font-medium text-rose-700">Decision follow-up at 6:00 PM</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <div class="mb-3 flex items-center justify-between">
                                <h4 class="text-xs font-semibold uppercase tracking-wide text-slate-500">Conversion Snapshot</h4>
                                <button class="text-xs font-semibold text-indigo-600">Edit</button>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="rounded-xl bg-slate-50 p-3">
                                    <p class="text-xs text-slate-500">Days in pipeline</p>
                                    <p class="mt-1 text-lg font-bold text-slate-900">8</p>
                                </div>
                                <div class="rounded-xl bg-slate-50 p-3">
                                    <p class="text-xs text-slate-500">Days in stage</p>
                                    <p class="mt-1 text-lg font-bold text-slate-900">1</p>
                                </div>
                                <div class="rounded-xl bg-slate-50 p-3">
                                    <p class="text-xs text-slate-500">Engagement</p>
                                    <p class="mt-1 text-lg font-bold text-emerald-700">87%</p>
                                </div>
                                <div class="rounded-xl bg-slate-50 p-3">
                                    <p class="text-xs text-slate-500">Likelihood</p>
                                    <p class="mt-1 text-lg font-bold text-indigo-700">91%</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="mb-3 flex items-center justify-between">
                                <h4 class="text-xs font-semibold uppercase tracking-wide text-slate-500">Activity Timeline</h4>
                                <button class="text-xs font-semibold text-indigo-600">View all</button>
                            </div>
                            <div class="space-y-4 border-l-2 border-slate-200 pl-4">
                                <div class="relative">
                                    <span class="absolute -left-[23px] top-1 h-3 w-3 rounded-full bg-indigo-600"></span>
                                    <p class="text-sm font-semibold text-slate-900">Added to system</p>
                                    <p class="text-xs text-slate-500">Apr 13, 2026 • Imported by Joey</p>
                                </div>
                                <div class="relative">
                                    <span class="absolute -left-[23px] top-1 h-3 w-3 rounded-full bg-sky-500"></span>
                                    <p class="text-sm font-semibold text-slate-900">Initial contact made</p>
                                    <p class="text-xs text-slate-500">Apr 14, 2026 • Intro private message sent</p>
                                </div>
                                <div class="relative">
                                    <span class="absolute -left-[23px] top-1 h-3 w-3 rounded-full bg-cyan-500"></span>
                                    <p class="text-sm font-semibold text-slate-900">Invited to presentation</p>
                                    <p class="text-xs text-slate-500">Apr 16, 2026 • Webinar registration completed</p>
                                </div>
                                <div class="relative">
                                    <span class="absolute -left-[23px] top-1 h-3 w-3 rounded-full bg-blue-500"></span>
                                    <p class="text-sm font-semibold text-slate-900">Presentation completed</p>
                                    <p class="text-xs text-slate-500">Today • Asked about sponsor tree and onboarding flow</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-xs font-semibold uppercase tracking-wide text-slate-500">Quick Actions</h4>
                            <div class="mt-3 grid grid-cols-2 gap-2">
                                <button class="rounded-xl bg-indigo-600 px-3 py-2.5 text-sm font-semibold text-white">Log Note</button>
                                <button class="rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-semibold text-slate-700">Create Task</button>
                                <button class="rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-semibold text-slate-700">Send Message</button>
                                <button class="rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-semibold text-slate-700">Schedule Call</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- AI Insights -->
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="mb-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Smart Insights</h3>
                            <p class="text-sm text-slate-500">Actionable recommendations based on pipeline behavior.</p>
                        </div>
                        <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700 ring-1 ring-inset ring-indigo-200">
                            AI Assisted
                        </span>
                    </div>

                    <div class="space-y-3">
                        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
                            <p class="text-sm font-semibold text-amber-900">Most prospects are stalling between Invited and Presented.</p>
                            <p class="mt-1 text-xs leading-5 text-amber-800">Consider sending reminder sequences 12 hours before the webinar and 1 hour before presentation time.</p>
                        </div>
                        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4">
                            <p class="text-sm font-semibold text-rose-900">7 Added prospects need first contact today.</p>
                            <p class="mt-1 text-xs leading-5 text-rose-800">Leads contacted within 24 hours are converting significantly better than delayed outreach.</p>
                        </div>
                        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
                            <p class="text-sm font-semibold text-emerald-900">Presented leads assigned to you convert 2.3x above team average.</p>
                            <p class="mt-1 text-xs leading-5 text-emerald-800">Your strongest zone is the post-presentation close. Prioritize personal follow-up here.</p>
                        </div>
                        <div class="rounded-2xl border border-indigo-200 bg-indigo-50 p-4">
                            <p class="text-sm font-semibold text-indigo-900">3 prospects are likely to join this week.</p>
                            <p class="mt-1 text-xs leading-5 text-indigo-800">Focus on Sarah Chen, Robert Ong, and Jenny Lopez for the highest conversion impact.</p>
                        </div>
                    </div>
                </div>

                <!-- Mini Analytics -->
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-slate-900">Performance Analytics</h3>
                        <p class="text-sm text-slate-500">Quick visual read of your current conversion health.</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <div class="mb-2 flex items-center justify-between text-xs">
                                <span class="font-semibold text-slate-700">Added → Contacted</span>
                                <span class="font-semibold text-slate-900">78.1%</span>
                            </div>
                            <div class="h-2 rounded-full bg-slate-100">
                                <div class="h-2 w-[78%] rounded-full bg-indigo-600"></div>
                            </div>
                        </div>
                        <div>
                            <div class="mb-2 flex items-center justify-between text-xs">
                                <span class="font-semibold text-slate-700">Contacted → Invited</span>
                                <span class="font-semibold text-slate-900">67.0%</span>
                            </div>
                            <div class="h-2 rounded-full bg-slate-100">
                                <div class="h-2 w-[67%] rounded-full bg-sky-600"></div>
                            </div>
                        </div>
                        <div>
                            <div class="mb-2 flex items-center justify-between text-xs">
                                <span class="font-semibold text-slate-700">Invited → Presented</span>
                                <span class="font-semibold text-slate-900">55.2%</span>
                            </div>
                            <div class="h-2 rounded-full bg-slate-100">
                                <div class="h-2 w-[55%] rounded-full bg-cyan-600"></div>
                            </div>
                        </div>
                        <div>
                            <div class="mb-2 flex items-center justify-between text-xs">
                                <span class="font-semibold text-slate-700">Presented → Followed Up</span>
                                <span class="font-semibold text-slate-900">73.0%</span>
                            </div>
                            <div class="h-2 rounded-full bg-slate-100">
                                <div class="h-2 w-[73%] rounded-full bg-blue-600"></div>
                            </div>
                        </div>
                        <div>
                            <div class="mb-2 flex items-center justify-between text-xs">
                                <span class="font-semibold text-slate-700">Followed Up → Joined</span>
                                <span class="font-semibold text-slate-900">70.4%</span>
                            </div>
                            <div class="h-2 rounded-full bg-slate-100">
                                <div class="h-2 w-[70%] rounded-full bg-violet-600"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-3">
                        <div class="rounded-xl bg-slate-50 p-3">
                            <p class="text-xs text-slate-500">Best Source</p>
                            <p class="mt-1 text-sm font-bold text-slate-900">Referrals</p>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-3">
                            <p class="text-xs text-slate-500">Best Opportunity</p>
                            <p class="mt-1 text-sm font-bold text-slate-900">WLA Membership</p>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-3">
                            <p class="text-xs text-slate-500">Follow-up Rate</p>
                            <p class="mt-1 text-sm font-bold text-slate-900">84%</p>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-3">
                            <p class="text-xs text-slate-500">Reactivation Win Rate</p>
                            <p class="mt-1 text-sm font-bold text-slate-900">22%</p>
                        </div>
                    </div>
                </div>
            </aside>
        </div>

        <!-- Lower Analytics Section -->
        <div class="mt-6 grid gap-6 xl:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-2">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Conversion Trend</h3>
                        <p class="text-sm text-slate-500">Illustrative chart container for daily/weekly conversion movement.</p>
                    </div>
                    <button class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700">Monthly</button>
                </div>

                <div class="flex h-72 items-end gap-3 rounded-2xl bg-slate-50 p-4">
                    <div class="flex flex-1 flex-col items-center gap-2">
                        <div class="w-full rounded-t-xl bg-indigo-500" style="height: 42%;"></div>
                        <span class="text-xs text-slate-500">W1</span>
                    </div>
                    <div class="flex flex-1 flex-col items-center gap-2">
                        <div class="w-full rounded-t-xl bg-indigo-500" style="height: 56%;"></div>
                        <span class="text-xs text-slate-500">W2</span>
                    </div>
                    <div class="flex flex-1 flex-col items-center gap-2">
                        <div class="w-full rounded-t-xl bg-indigo-500" style="height: 49%;"></div>
                        <span class="text-xs text-slate-500">W3</span>
                    </div>
                    <div class="flex flex-1 flex-col items-center gap-2">
                        <div class="w-full rounded-t-xl bg-indigo-500" style="height: 68%;"></div>
                        <span class="text-xs text-slate-500">W4</span>
                    </div>
                    <div class="flex flex-1 flex-col items-center gap-2">
                        <div class="w-full rounded-t-xl bg-indigo-500" style="height: 74%;"></div>
                        <span class="text-xs text-slate-500">W5</span>
                    </div>
                    <div class="flex flex-1 flex-col items-center gap-2">
                        <div class="w-full rounded-t-xl bg-indigo-500" style="height: 63%;"></div>
                        <span class="text-xs text-slate-500">W6</span>
                    </div>
                    <div class="flex flex-1 flex-col items-center gap-2">
                        <div class="w-full rounded-t-xl bg-indigo-500" style="height: 82%;"></div>
                        <span class="text-xs text-slate-500">W7</span>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-5">
                    <h3 class="text-lg font-bold text-slate-900">Team Leaderboard</h3>
                    <p class="text-sm text-slate-500">Top performers by Joined conversion.</p>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Joey Lustre</p>
                            <p class="text-xs text-slate-500">19 joined • 91% close score</p>
                        </div>
                        <span class="text-sm font-bold text-emerald-700">#1</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Maria Cruz</p>
                            <p class="text-xs text-slate-500">13 joined • 79% close score</p>
                        </div>
                        <span class="text-sm font-bold text-sky-700">#2</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">John Reyes</p>
                            <p class="text-xs text-slate-500">10 joined • 72% close score</p>
                        </div>
                        <span class="text-sm font-bold text-violet-700">#3</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Angela Tan</p>
                            <p class="text-xs text-slate-500">7 joined • 66% close score</p>
                        </div>
                        <span class="text-sm font-bold text-slate-700">#4</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection