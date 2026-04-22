<section id="how" class="bg-[#0b1c1e] text-white py-24 font-sans scroll-mt-20 animate-fade-in">
  </div>
  <script>
  // Smooth scroll and animate section on navigation
  document.addEventListener('DOMContentLoaded', function () {
    // Add smooth scroll to all anchor links
    document.querySelectorAll('a[href^="#"]').forEach(link => {
      link.addEventListener('click', function (e) {
        const targetId = this.getAttribute('href').substring(1);
        const target = document.getElementById(targetId);
        if (target) {
          e.preventDefault();
          target.classList.remove('animate-fade-in');
          window.scrollTo({
            top: target.getBoundingClientRect().top + window.scrollY - 80,
            behavior: 'smooth'
          });
          setTimeout(() => {
            target.classList.add('animate-fade-in');
          }, 200);
        }
      });
    });
  });
  </script>
  <style>
  @keyframes fade-in {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .animate-fade-in {
    animation: fade-in 0.7s cubic-bezier(.4,0,.2,1);
  }
  </style>
  <div class="container mx-auto px-6 text-center mt-4">
    <h2 class="text-4xl md:text-5xl font-bold leading-tight mb-6">
      How It Works: Your Path to <span class="text-orange-400">Financial Freedom</span>
    </h2>
    <p class="text-slate-300 text-lg md:text-xl max-w-4xl mx-auto mb-16 leading-relaxed">
      Discover the revolutionary 4-step system that's transforming ordinary people into wealthy entrepreneurs. Watch thousands build permanent legacies with just one network.
    </p>
    <!-- Video Card -->
    <div class="flex flex-col items-center justify-center mb-12">
      <div class="w-full max-w-3xl rounded-2xl overflow-hidden shadow-lg bg-[#153c3a] border border-teal-200/20 relative">
        <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?q=80&w=1600&auto=format&fit=crop" alt="How It Works System Diagram" class="w-full h-64 md:h-72 object-cover">
        <button class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 bg-emerald-400 hover:bg-emerald-500 text-white rounded-full w-16 h-16 flex items-center justify-center shadow-lg border-4 border-white/30">
          <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.333-5.89a1.5 1.5 0 000-2.538L6.3 2.841z" /></svg>
        </button>
        <div class="absolute bottom-0 left-0 w-full px-4 pb-6 pt-4 bg-gradient-to-t from-[#153c3a]/90 to-transparent flex flex-col items-center">
          <h3 class="text-white text-xl md:text-2xl font-bold mb-1">The Complete System Revealed</h3>
          <p class="text-emerald-100 text-sm md:text-base mb-2">Watch this 12-minute video to understand exactly how our "recruit once, benefit always" system creates permanent wealth streams</p>
        </div>
      </div>
      <div class="flex flex-col md:flex-row gap-4 mt-6 w-full max-w-2xl justify-center">
        <button class="px-8 py-3 bg-yellow-400 hover:bg-yellow-500 text-white font-semibold rounded-md shadow transition text-base flex items-center justify-center">
          <span class="font-bold mr-2">▶</span> Watch Full Explanation
        </button>
        <button class="px-8 py-3 bg-slate-700 hover:bg-slate-600 text-white font-semibold rounded-md shadow border border-slate-600 transition text-base flex items-center justify-center">
          Start Building Now
          <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </button>
      </div>
    </div>

    <!-- 4-Step Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 mt-8">
      <!-- Card 1 -->
      <div class="bg-[#17615c] rounded-xl border border-teal-200/20 p-7 flex flex-col min-h-[260px] justify-between text-left relative">
        <div class="flex items-center mb-3">
          <div class="w-12 h-12 rounded-lg bg-emerald-500/20 flex items-center justify-center mr-4">
            <svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
          </div>
          <span class="text-3xl font-bold text-emerald-200">01</span>
          <span class="ml-3 px-3 py-1 text-xs font-bold uppercase tracking-wider text-white bg-yellow-400 rounded-full">Free to join</span>
        </div>
        <h3 class="text-white text-xl font-bold mb-2">Join Our Revolutionary Platform</h3>
        <p class="text-teal-100 mb-4">Sign up in minutes and gain immediate access to our exclusive network of premium MLM opportunities. No waiting, no complicated processes – just instant access to wealth-building potential.</p>
        <button class="mt-auto px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-full font-semibold text-sm shadow transition">Learn More Details</button>
      </div>
      <!-- Card 2 -->
      <div class="bg-[#17615c] rounded-xl border border-teal-200/20 p-7 flex flex-col min-h-[260px] justify-between text-left relative">
        <div class="flex items-center mb-3">
          <div class="w-12 h-12 rounded-lg bg-emerald-500/20 flex items-center justify-center mr-4">
            <svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
          </div>
          <span class="text-3xl font-bold text-emerald-200">02</span>
          <span class="ml-3 px-3 py-1 text-xs font-bold uppercase tracking-wider text-white bg-yellow-400 rounded-full">Build your team once, benefit forever</span>
        </div>
        <h3 class="text-white text-xl font-bold mb-2">Build Your Network Once</h3>
        <p class="text-teal-100 mb-4">Use our proven recruiting strategies and advanced tools to build your downline. This is the ONLY network you'll ever need to create – we handle the rest automatically.</p>
        <button class="mt-auto px-5 py-2 bg-violet-600 hover:bg-violet-700 text-white rounded-full font-semibold text-sm shadow transition">Learn More Details</button>
      </div>
      <!-- Card 3 -->
      <div class="bg-[#17615c] rounded-xl border border-teal-200/20 p-7 flex flex-col min-h-[260px] justify-between text-left relative">
        <div class="flex items-center mb-3">
          <div class="w-12 h-12 rounded-lg bg-emerald-500/20 flex items-center justify-center mr-4">
            <svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
          </div>
          <span class="text-3xl font-bold text-emerald-200">03</span>
          <span class="ml-3 px-3 py-1 text-xs font-bold uppercase tracking-wider text-white bg-yellow-400 rounded-full">Instant activation</span>
        </div>
        <h3 class="text-white text-xl font-bold mb-2">Automatic Multi-Company Access</h3>
        <p class="text-teal-100 mb-4">Your network instantly gains access to ALL our affiliated MLM companies. No rebuilding, no re-recruiting – your existing team starts earning from multiple sources immediately.</p>
        <button class="mt-auto px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-full font-semibold text-sm shadow transition">Learn More Details</button>
      </div>
      <!-- Card 4 -->
      <div class="bg-[#17615c] rounded-xl border border-teal-200/20 p-7 flex flex-col min-h-[260px] justify-between text-left relative">
        <div class="flex items-center mb-3">
          <div class="w-12 h-12 rounded-lg bg-emerald-500/20 flex items-center justify-center mr-4">
            <svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
          </div>
          <span class="text-3xl font-bold text-emerald-200">04</span>
          <span class="ml-3 px-3 py-1 text-xs font-bold uppercase tracking-wider text-white bg-yellow-400 rounded-full">Unlimited earning potential</span>
        </div>
        <h3 class="text-white text-xl font-bold mb-2">Earn Exponential Income</h3>
        <p class="text-teal-100 mb-4">Watch your wealth multiply as your single network generates income from 10+ companies simultaneously. Protected legacy means your earnings continue growing forever.</p>
        <button class="mt-auto px-5 py-2 bg-violet-600 hover:bg-violet-700 text-white rounded-full font-semibold text-sm shadow transition">Learn More Details</button>
      </div>
    </div>
  </div>
</section>
