@extends('layouts.app')

@section('title', 'Cookie Policy | Wealth Legacy Alliance')

@section('content')

  {{-- Page header --}}
  <section class="wla-hero relative overflow-hidden pt-32 pb-16 text-white sm:pt-36 sm:pb-20">
    <div class="pointer-events-none absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 20% 20%, rgba(255,255,255,0.25), transparent 40%);"></div>
    <div class="relative mx-auto max-w-4xl px-6 text-center">
      <span class="inline-flex items-center rounded-full bg-white/10 px-4 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-300">
        Legal
      </span>
      <h1 class="mt-5 text-4xl font-extrabold sm:text-5xl">Cookie Policy</h1>
      <p class="mx-auto mt-4 max-w-2xl text-[15px] leading-relaxed text-slate-300">
        This policy explains how Wealth Legacy Alliance uses cookies and similar technologies. It is a
        starting template and can be updated at any time to reflect our actual practices.
      </p>
      <p class="mt-4 text-sm text-slate-400">Last updated: {{ now()->format('F j, Y') }}</p>
    </div>
  </section>

  {{-- Content --}}
  <section class="bg-slate-50 py-16 transition-colors duration-300 dark:bg-slate-950">
    <div class="mx-auto grid max-w-6xl grid-cols-1 gap-10 px-6 lg:grid-cols-[260px_1fr]">

      {{-- Table of contents --}}
      <aside class="hidden lg:block">
        <nav class="sticky top-28 space-y-1 rounded-2xl border border-slate-200 bg-white p-5 text-sm shadow-sm dark:border-slate-800 dark:bg-slate-900">
          <p class="mb-3 text-xs font-bold uppercase tracking-wide text-slate-400 dark:text-slate-500">On this page</p>
          <a href="#introduction" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">1. Introduction</a>
          <a href="#what-are-cookies" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">2. What Are Cookies</a>
          <a href="#how-we-use-cookies" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">3. How We Use Cookies</a>
          <a href="#types-of-cookies" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">4. Types of Cookies We Use</a>
          <a href="#third-party-cookies" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">5. Third-Party Cookies</a>
          <a href="#managing-cookies" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">6. Managing Your Cookie Preferences</a>
          <a href="#changes" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">7. Changes to This Policy</a>
          <a href="#contact" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">8. Contact Us</a>
        </nav>
      </aside>

      {{-- Policy body --}}
      <article class="space-y-10 rounded-2xl border border-slate-200 bg-white p-8 text-slate-700 shadow-sm transition-colors duration-300 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 sm:p-10">

        <div id="introduction" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">1. Introduction</h2>
          <p class="leading-relaxed">
            This Cookie Policy explains how Wealth Legacy Alliance ("WLA", "we", "us", or "our") uses cookies
            and similar tracking technologies when you visit our website or use our platform. It should be
            read together with our <a href="{{ route('privacy-policy') }}" class="font-semibold text-emerald-700 underline dark:text-emerald-400">Privacy Policy</a>.
          </p>
          <p class="leading-relaxed">
            This is a placeholder document intended to be reviewed and customized to match our actual cookie
            usage and applicable law before publishing as our official Cookie Policy.
          </p>
        </div>

        <div id="what-are-cookies" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">2. What Are Cookies</h2>
          <p class="leading-relaxed">
            Cookies are small text files placed on your device when you visit a website. They are widely used
            to make websites function properly, remember your preferences, and provide information to the
            site owner. Similar technologies such as local storage and pixels may serve comparable purposes.
          </p>
        </div>

        <div id="how-we-use-cookies" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">3. How We Use Cookies</h2>
          <p class="leading-relaxed">We use cookies and similar technologies to:</p>
          <ul class="list-disc space-y-2 pl-6 leading-relaxed">
            <li>Keep you signed in and maintain your session while you use the member dashboard.</li>
            <li>Remember your preferences, such as your selected theme (light or dark mode).</li>
            <li>Understand how the platform is used so we can improve performance and features.</li>
            <li>Protect the security of your account and detect fraudulent activity.</li>
          </ul>
        </div>

        <div id="types-of-cookies" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">4. Types of Cookies We Use</h2>
          <ul class="list-disc space-y-2 pl-6 leading-relaxed">
            <li><strong>Essential cookies:</strong> required for core functionality such as authentication and session management. The platform cannot function properly without these.</li>
            <li><strong>Preference cookies:</strong> remember choices you make, such as display and theme settings.</li>
            <li><strong>Analytics cookies:</strong> help us understand how members use the platform so we can improve it over time.</li>
          </ul>
        </div>

        <div id="third-party-cookies" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">5. Third-Party Cookies</h2>
          <p class="leading-relaxed">
            Some cookies may be placed by third-party services we use to operate or improve the platform, such
            as analytics or support providers. These third parties have their own privacy and cookie practices,
            which we do not control.
          </p>
        </div>

        <div id="managing-cookies" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">6. Managing Your Cookie Preferences</h2>
          <p class="leading-relaxed">
            Most browsers let you control cookies through their settings, including blocking or deleting them.
            Please note that disabling essential cookies may prevent parts of the platform, such as signing in
            or staying logged in, from working correctly.
          </p>
        </div>

        <div id="changes" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">7. Changes to This Policy</h2>
          <p class="leading-relaxed">
            We may update this Cookie Policy from time to time. We will notify you of any material changes by
            posting the updated policy on this page and updating the "Last updated" date above.
          </p>
        </div>

        <div id="contact" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">8. Contact Us</h2>
          <p class="leading-relaxed">
            If you have questions about this Cookie Policy, please contact us at:
          </p>
          <p class="rounded-xl bg-slate-50 p-4 leading-relaxed dark:bg-slate-800">
            Wealth Legacy Alliance<br />
            support@wealthlegacy.com<br />
            1-800-LEGACY-1<br />
            123 Legacy Boulevard, Wealth City, WC 12345
          </p>
        </div>

      </article>
    </div>
  </section>

  @include('landing.footer')

@endsection
