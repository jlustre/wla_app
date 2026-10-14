@extends('layouts.app')

@section('title', 'Income Disclosure | Wealth Legacy Alliance')

@section('content')

  {{-- Page header --}}
  <section class="wla-hero relative overflow-hidden pt-32 pb-16 text-white sm:pt-36 sm:pb-20">
    <div class="pointer-events-none absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 20% 20%, rgba(255,255,255,0.25), transparent 40%);"></div>
    <div class="relative mx-auto max-w-4xl px-6 text-center">
      <span class="inline-flex items-center rounded-full bg-white/10 px-4 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-300">
        Legal
      </span>
      <h1 class="mt-5 text-4xl font-extrabold sm:text-5xl">Income Disclosure</h1>
      <p class="mx-auto mt-4 max-w-2xl text-[15px] leading-relaxed text-slate-300">
        An honest look at what members can realistically expect to earn. It is a starting template and can
        be updated at any time to reflect our actual figures and legal requirements.
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
          <a href="#no-guarantee" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">2. No Guarantee of Income</a>
          <a href="#typical-results" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">3. Typical Results</a>
          <a href="#factors" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">4. Factors Affecting Earnings</a>
          <a href="#affiliated-companies" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">5. Affiliated Company Earnings</a>
          <a href="#testimonials" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">6. Testimonials &amp; Examples</a>
          <a href="#changes" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">7. Changes to This Disclosure</a>
          <a href="#contact" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">8. Contact Us</a>
        </nav>
      </aside>

      {{-- Disclosure body --}}
      <article class="space-y-10 rounded-2xl border border-slate-200 bg-white p-8 text-slate-700 shadow-sm transition-colors duration-300 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 sm:p-10">

        <div id="introduction" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">1. Introduction</h2>
          <p class="leading-relaxed">
            Wealth Legacy Alliance ("WLA", "we", "us", or "our") believes members deserve a clear and honest
            picture of what participation in the platform and its affiliated companies can realistically
            produce. This Income Disclosure is provided to set accurate expectations before you join or invite
            others to join.
          </p>
          <p class="leading-relaxed">
            This is a placeholder document intended to be reviewed and replaced with our actual, verified
            earnings data before publishing as our official Income Disclosure.
          </p>
        </div>

        <div id="no-guarantee" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">2. No Guarantee of Income</h2>
          <div class="rounded-2xl border border-amber-300/60 bg-amber-50 p-4 dark:border-amber-500/30 dark:bg-amber-500/10">
            <p class="leading-relaxed text-amber-900 dark:text-amber-200">
              WLA does not guarantee any level of income, earnings, or business success. Joining WLA or any
              affiliated company does not by itself produce income. Any figures referenced by members,
              marketing materials, or this disclosure are estimates or examples only, not promises.
            </p>
          </div>
        </div>

        <div id="typical-results" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">3. Typical Results</h2>
          <p class="leading-relaxed">
            As with most network-based business opportunities, the majority of members earn modest amounts or
            no income at all, while a small percentage of highly active members earn significantly more. Actual
            results vary widely and depend on individual effort, skill, time invested, and market conditions.
            Once available, we intend to publish aggregate statistics here showing the average and median
            earnings of active members, including the percentage who earn nothing.
          </p>
        </div>

        <div id="factors" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">4. Factors Affecting Earnings</h2>
          <p class="leading-relaxed">Income potential is influenced by many factors, including but not limited to:</p>
          <ul class="list-disc space-y-2 pl-6 leading-relaxed">
            <li>The amount of time and effort a member consistently invests.</li>
            <li>Sales, communication, and leadership skills.</li>
            <li>The size and activity level of a member's network.</li>
            <li>Which affiliated companies a member chooses to join and when.</li>
            <li>General market and economic conditions outside of WLA's control.</li>
          </ul>
        </div>

        <div id="affiliated-companies" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">5. Affiliated Company Earnings</h2>
          <p class="leading-relaxed">
            WLA tracks your network structure across the alliance, but each affiliated company maintains its
            own compensation plan, pricing, and payout records. Any income earned through a specific company is
            governed by that company's own terms and income disclosures, which may be provided separately. WLA
            is not responsible for the accuracy of earnings claims made by affiliated companies.
          </p>
        </div>

        <div id="testimonials" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">6. Testimonials &amp; Examples</h2>
          <p class="leading-relaxed">
            Any testimonials, income examples, or success stories shared by WLA or its members represent
            individual experiences and are not representative of what a typical member should expect to earn.
            Past results of any member do not guarantee similar future results for you.
          </p>
        </div>

        <div id="changes" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">7. Changes to This Disclosure</h2>
          <p class="leading-relaxed">
            We may update this Income Disclosure from time to time, including as real earnings data becomes
            available. We will notify you of any material changes by posting the updated disclosure on this
            page and updating the "Last updated" date above.
          </p>
        </div>

        <div id="contact" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">8. Contact Us</h2>
          <p class="leading-relaxed">
            If you have questions about this Income Disclosure, please contact us at:
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
