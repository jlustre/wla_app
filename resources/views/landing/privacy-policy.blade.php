@extends('layouts.app')

@section('title', 'Privacy Policy | Wealth Legacy Alliance')

@section('content')

  {{-- Page header --}}
  <section class="wla-hero relative overflow-hidden pt-32 pb-16 text-white sm:pt-36 sm:pb-20">
    <div class="pointer-events-none absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 20% 20%, rgba(255,255,255,0.25), transparent 40%);"></div>
    <div class="relative mx-auto max-w-4xl px-6 text-center">
      <span class="inline-flex items-center rounded-full bg-white/10 px-4 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-300">
        Legal
      </span>
      <h1 class="mt-5 text-4xl font-extrabold sm:text-5xl">Privacy Policy</h1>
      <p class="mx-auto mt-4 max-w-2xl text-[15px] leading-relaxed text-slate-300">
        This policy explains what information Wealth Legacy Alliance collects, how we use it, and the choices
        you have. It is a starting template and can be updated at any time to reflect our actual practices.
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
          <a href="#information-we-collect" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">2. Information We Collect</a>
          <a href="#how-we-use-information" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">3. How We Use Information</a>
          <a href="#how-we-share-information" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">4. How We Share Information</a>
          <a href="#cookies" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">5. Cookies &amp; Tracking</a>
          <a href="#data-security" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">6. Data Security</a>
          <a href="#data-retention" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">7. Data Retention</a>
          <a href="#your-rights" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">8. Your Rights &amp; Choices</a>
          <a href="#childrens-privacy" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">9. Children's Privacy</a>
          <a href="#changes" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">10. Changes to This Policy</a>
          <a href="#contact" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">11. Contact Us</a>
        </nav>
      </aside>

      {{-- Policy body --}}
      <article class="space-y-10 rounded-2xl border border-slate-200 bg-white p-8 text-slate-700 shadow-sm transition-colors duration-300 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 sm:p-10">

        <div id="introduction" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">1. Introduction</h2>
          <p class="leading-relaxed">
            Wealth Legacy Alliance ("WLA", "we", "us", or "our") respects your privacy and is committed to
            protecting it through this Privacy Policy. This policy describes the types of information we may
            collect from you or that you may provide when you visit our website or use our platform, and our
            practices for collecting, using, maintaining, protecting, and disclosing that information.
          </p>
          <p class="leading-relaxed">
            By using our platform, you agree to the collection and use of information in accordance with this
            policy. This is a placeholder document intended to be reviewed and customized to match our actual
            data practices before publishing as our official policy.
          </p>
        </div>

        <div id="information-we-collect" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">2. Information We Collect</h2>
          <p class="leading-relaxed">We may collect the following categories of information:</p>
          <ul class="list-disc space-y-2 pl-6 leading-relaxed">
            <li><strong>Account information:</strong> name, username, email address, phone number, and password when you register.</li>
            <li><strong>Sponsorship &amp; network data:</strong> your sponsor, downline relationships, and invitation links used to join the alliance.</li>
            <li><strong>Profile &amp; company data:</strong> information related to the companies you join through the platform and your activity within those dashboards.</li>
            <li><strong>Usage data:</strong> pages visited, features used, and actions taken while using the platform.</li>
            <li><strong>Device &amp; technical data:</strong> IP address, browser type, device identifiers, and similar technical details.</li>
          </ul>
        </div>

        <div id="how-we-use-information" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">3. How We Use Information</h2>
          <p class="leading-relaxed">We use the information we collect to:</p>
          <ul class="list-disc space-y-2 pl-6 leading-relaxed">
            <li>Create and manage your account and sponsorship relationships.</li>
            <li>Operate, maintain, and improve the platform and its features.</li>
            <li>Track your network structure and display dashboards, reports, and activity logs.</li>
            <li>Communicate with you about updates, support requests, and account activity.</li>
            <li>Detect, investigate, and prevent fraudulent or unauthorized activity.</li>
            <li>Comply with legal obligations.</li>
          </ul>
        </div>

        <div id="how-we-share-information" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">4. How We Share Information</h2>
          <p class="leading-relaxed">
            We do not sell your personal information. We may share information with affiliated companies you
            choose to join through the platform, service providers who help us operate the platform, and
            authorities when required by law. Any such sharing is limited to what is necessary for the stated
            purpose.
          </p>
        </div>

        <div id="cookies" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">5. Cookies &amp; Tracking</h2>
          <p class="leading-relaxed">
            We use cookies and similar technologies to keep you signed in, remember your preferences, and
            understand how the platform is used. You can control cookies through your browser settings, though
            disabling them may affect how parts of the platform function.
          </p>
        </div>

        <div id="data-security" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">6. Data Security</h2>
          <p class="leading-relaxed">
            We implement reasonable administrative, technical, and physical safeguards designed to protect your
            information. However, no method of transmission or storage is completely secure, and we cannot
            guarantee absolute security.
          </p>
        </div>

        <div id="data-retention" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">7. Data Retention</h2>
          <p class="leading-relaxed">
            We retain personal information for as long as your account is active or as needed to provide you
            with the platform, comply with our legal obligations, resolve disputes, and enforce our agreements.
          </p>
        </div>

        <div id="your-rights" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">8. Your Rights &amp; Choices</h2>
          <p class="leading-relaxed">
            Depending on your location, you may have the right to access, correct, update, or request deletion
            of your personal information. You may also have the right to object to or restrict certain
            processing. To exercise these rights, contact us using the details below.
          </p>
        </div>

        <div id="childrens-privacy" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">9. Children's Privacy</h2>
          <p class="leading-relaxed">
            Our platform is not directed to individuals under the age of 18, and we do not knowingly collect
            personal information from children. If we become aware that we have collected such information, we
            will take steps to delete it.
          </p>
        </div>

        <div id="changes" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">10. Changes to This Policy</h2>
          <p class="leading-relaxed">
            We may update this Privacy Policy from time to time. We will notify you of any material changes by
            posting the updated policy on this page and updating the "Last updated" date above.
          </p>
        </div>

        <div id="contact" class="space-y-3">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">11. Contact Us</h2>
          <p class="leading-relaxed">
            If you have questions about this Privacy Policy or how we handle your information, please contact
            us at:
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
