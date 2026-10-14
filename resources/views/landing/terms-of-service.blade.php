@extends('layouts.app')

@section('title', 'Terms of Service | Wealth Legacy Alliance')

@section('content')

  {{-- Page header --}}
  <section class="wla-hero relative overflow-hidden pt-32 pb-16 text-white sm:pt-36 sm:pb-20">
    <div class="pointer-events-none absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 20% 20%, rgba(255,255,255,0.25), transparent 40%);"></div>
    <div class="relative mx-auto max-w-4xl px-6 text-center">
      <span class="inline-flex items-center rounded-full bg-white/10 px-4 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-300">
        Legal
      </span>
      <h1 class="mt-5 text-4xl font-extrabold sm:text-5xl">Terms of Service</h1>
      <p class="mx-auto mt-4 max-w-2xl text-[15px] leading-relaxed text-slate-300">
        These terms govern your access to and use of Wealth Legacy Alliance. It is a starting template and
        can be updated at any time to reflect our actual policies and legal requirements.
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
          <a href="#eligibility" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">2. Eligibility &amp; Accounts</a>
          <a href="#membership" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">3. Membership &amp; Sponsorship</a>
          <a href="#affiliated-companies" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">4. Affiliated Companies</a>
          <a href="#acceptable-use" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">5. Acceptable Use</a>
          <a href="#fees" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">6. Fees &amp; Payments</a>
          <a href="#intellectual-property" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">7. Intellectual Property</a>
          <a href="#termination" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">8. Suspension &amp; Termination</a>
          <a href="#disclaimers" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">9. Disclaimers</a>
          <a href="#liability" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">10. Limitation of Liability</a>
          <a href="#governing-law" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">11. Governing Law</a>
          <a href="#changes" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">12. Changes to These Terms</a>
          <a href="#contact" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">13. Contact Us</a>
        </nav>
      </aside>

      {{-- Terms body --}}
      <article class="space-y-10 rounded-2xl border border-slate-200 bg-white p-8 text-slate-700 shadow-sm transition-colors duration-300 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 sm:p-10">

        <div id="introduction" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">1. Introduction</h2>
          <p class="leading-relaxed">
            These Terms of Service ("Terms") govern your access to and use of Wealth Legacy Alliance ("WLA",
            "we", "us", or "our"), including our website, member dashboard, and related services (collectively,
            the "Platform"). By creating an account or otherwise using the Platform, you agree to be bound by
            these Terms.
          </p>
          <p class="leading-relaxed">
            This is a placeholder document intended to be reviewed and customized by legal counsel to match our
            actual business practices and applicable law before publishing as our official Terms of Service.
          </p>
        </div>

        <div id="eligibility" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">2. Eligibility &amp; Accounts</h2>
          <p class="leading-relaxed">
            You must be at least 18 years old and able to form a binding contract to use the Platform. When you
            register, you agree to provide accurate and complete information and to keep your account
            credentials confidential. You are responsible for all activity that occurs under your account.
          </p>
        </div>

        <div id="membership" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">3. Membership &amp; Sponsorship</h2>
          <p class="leading-relaxed">
            Membership in WLA is invitation-based. New members join through an existing member's invitation
            link, and that member becomes their sponsor within the WLA network. By registering, you acknowledge
            and accept the sponsorship structure, downline tracking, and positioning rules described on our
            platform and in member communications.
          </p>
          <ul class="list-disc space-y-2 pl-6 leading-relaxed">
            <li>Your sponsor relationship inside WLA is established at registration and tracked by the Platform.</li>
            <li>Sponsorship within any individual affiliated company depends on your own active participation in that company, independent of your WLA network position.</li>
            <li>WLA may update placement, spillover, or positioning rules from time to time to maintain the integrity of the network.</li>
          </ul>
        </div>

        <div id="affiliated-companies" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">4. Affiliated Companies</h2>
          <p class="leading-relaxed">
            WLA provides access to multiple affiliated companies and opportunities. WLA does not create, own, or
            control the products, services, compensation plans, or payout systems of any affiliated company.
            Each affiliated company is solely responsible for its own terms, compensation, and performance. WLA's
            role is limited to tracking your network structure and facilitating your connection to opportunities
            within the alliance.
          </p>
        </div>

        <div id="acceptable-use" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">5. Acceptable Use</h2>
          <p class="leading-relaxed">When using the Platform, you agree not to:</p>
          <ul class="list-disc space-y-2 pl-6 leading-relaxed">
            <li>Provide false, misleading, or fraudulent information during registration or at any other time.</li>
            <li>Misrepresent WLA, its affiliated companies, or potential earnings to prospective members.</li>
            <li>Attempt to interfere with, disrupt, or gain unauthorized access to the Platform or other members' accounts.</li>
            <li>Use the Platform for any unlawful, harassing, or abusive purpose.</li>
            <li>Circumvent or manipulate the sponsorship, placement, or positioning structure of the network.</li>
          </ul>
        </div>

        <div id="fees" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">6. Fees &amp; Payments</h2>
          <p class="leading-relaxed">
            Some features of the Platform, or participation in specific affiliated companies, may require the
            payment of fees. Any applicable fees, billing terms, and refund conditions will be disclosed to you
            before you are charged. Fees charged by affiliated companies are governed by that company's own
            terms, not by WLA.
          </p>
        </div>

        <div id="intellectual-property" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">7. Intellectual Property</h2>
          <p class="leading-relaxed">
            The Platform, including its design, branding, text, graphics, and underlying software, is owned by
            WLA or its licensors and is protected by applicable intellectual property laws. You may not copy,
            modify, distribute, or create derivative works from the Platform without our prior written consent.
          </p>
        </div>

        <div id="termination" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">8. Suspension &amp; Termination</h2>
          <p class="leading-relaxed">
            We may suspend or terminate your access to the Platform if you violate these Terms, engage in
            fraudulent or harmful conduct, or for any other reason at our reasonable discretion. You may also
            close your account at any time by contacting us. Termination does not affect obligations that by
            their nature should survive, such as outstanding payments or confidentiality commitments.
          </p>
        </div>

        <div id="disclaimers" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">9. Disclaimers</h2>
          <p class="leading-relaxed">
            The Platform is provided "as is" and "as available" without warranties of any kind, express or
            implied. WLA does not guarantee any specific level of income, team growth, or business results.
            Your success depends on your own efforts, the affiliated companies you choose to join, and factors
            outside of our control.
          </p>
        </div>

        <div id="liability" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">10. Limitation of Liability</h2>
          <p class="leading-relaxed">
            To the fullest extent permitted by law, WLA and its officers, employees, and affiliates will not be
            liable for any indirect, incidental, special, or consequential damages arising from your use of the
            Platform or your participation in any affiliated company, even if we have been advised of the
            possibility of such damages.
          </p>
        </div>

        <div id="governing-law" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">11. Governing Law</h2>
          <p class="leading-relaxed">
            These Terms are governed by the laws of the jurisdiction in which WLA operates, without regard to
            conflict-of-law principles. Any disputes arising from these Terms or your use of the Platform will
            be resolved in the courts of that jurisdiction, unless otherwise required by applicable law.
          </p>
        </div>

        <div id="changes" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">12. Changes to These Terms</h2>
          <p class="leading-relaxed">
            We may update these Terms from time to time. We will notify you of any material changes by posting
            the updated Terms on this page and updating the "Last updated" date above. Continued use of the
            Platform after changes take effect constitutes acceptance of the revised Terms.
          </p>
        </div>

        <div id="contact" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">13. Contact Us</h2>
          <p class="leading-relaxed">
            If you have questions about these Terms of Service, please contact us at:
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
