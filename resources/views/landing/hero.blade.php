<section id="hero" class="relative min-h-screen flex items-center overflow-hidden">
    <div class="absolute inset-0 z-0 w-full h-full overflow-hidden">
        <!-- Hero slider images -->
        <img src="/images/landing/hero/hero1.png" alt="Hero 1" class="hero-slide w-full h-full object-cover object-top absolute inset-0 transition-opacity duration-700 opacity-100" style="z-index:1;">
        <img src="/images/landing/hero/hero2.png" alt="Hero 2" class="hero-slide w-full h-full object-cover object-top absolute inset-0 transition-opacity duration-700 opacity-0" style="z-index:1;">
        <img src="/images/landing/hero/hero3.png" alt="Hero 3" class="hero-slide w-full h-full object-cover object-top absolute inset-0 transition-opacity duration-700 opacity-0" style="z-index:1;">
        <img src="/images/landing/hero/hero4.png" alt="Hero 4" class="hero-slide w-full h-full object-cover object-top absolute inset-0 transition-opacity duration-700 opacity-0" style="z-index:1;">
        <img src="/images/landing/hero/hero5.png" alt="Hero 5" class="hero-slide w-full h-full object-cover object-top absolute inset-0 transition-opacity duration-700 opacity-0" style="z-index:1;">
        <img src="/images/landing/hero/hero6.png" alt="Hero 6" class="hero-slide w-full h-full object-cover object-top absolute inset-0 transition-opacity duration-700 opacity-0" style="z-index:1;">
        <!-- Add more images as needed, just duplicate the line above and set opacity-0 for hidden ones -->
        <div class="absolute inset-0 hero-overlay" style="z-index:10;"></div>
        <!-- Slider controls -->
        <div class="absolute left-0 right-0 bottom-6 flex justify-center z-20 space-x-3">
            <button class="hero-dot w-3 h-3 rounded-full bg-white/70 hover:bg-white transition-all border-2 border-white" data-slide="0"></button>
            <button class="hero-dot w-3 h-3 rounded-full bg-white/40 hover:bg-white transition-all border-2 border-white" data-slide="1"></button>
            <button class="hero-dot w-3 h-3 rounded-full bg-white/40 hover:bg-white transition-all border-2 border-white" data-slide="2"></button>
            <button class="hero-dot w-3 h-3 rounded-full bg-white/40 hover:bg-white transition-all border-2 border-white" data-slide="3"></button>
            <button class="hero-dot w-3 h-3 rounded-full bg-white/40 hover:bg-white transition-all border-2 border-white" data-slide="4"></button>
            <button class="hero-dot w-3 h-3 rounded-full bg-white/40 hover:bg-white transition-all border-2 border-white" data-slide="5"></button>
        </div>
        <button id="hero-prev" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-slate-900/40 hover:bg-slate-900/70 text-white rounded-full w-10 h-10 flex items-center justify-center focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </button>
        <button id="hero-next" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-slate-900/40 hover:bg-slate-900/70 text-white rounded-full w-10 h-10 flex items-center justify-center focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        </button>
    </div>
    <div class="container mx-auto px-6 relative z-10 pt-20">
        <div class="max-w-3xl text-center md:text-left">
            <span class="inline-block px-4 py-1.5 mb-6 text-xs font-bold tracking-widest text-emerald-400 uppercase bg-emerald-400/10 border border-emerald-400/20 rounded-full">MLM Growth Platform</span>
            <h1 class="text-5xl md:text-7xl font-bold text-white leading-tight mb-4">Build Your Team Once.<br /><span class="text-emerald-400">Benefit Always.</span></h1>
            <h2 class="text-2xl md:text-3xl font-light text-slate-300 mb-6">Revolutionary MLM Platform</h2>
            <p class="text-lg md:text-xl text-slate-400 mb-10 max-w-xl leading-relaxed">Join the only MLM platform that lets you build your network once and benefit from multiple income streams forever.</p>
            <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 justify-center md:justify-start">
                @guest
                <a href="/register" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-500 text-white rounded-full font-bold text-lg transition transform hover:-translate-y-1 shadow-xl shadow-emerald-900/40 text-center md:inline-block">Start Building Your Legacy</a>
                @endguest
                <button class="px-8 py-4 bg-transparent border border-white/40 hover:bg-white/10 text-white rounded-full font-bold text-lg transition">Watch Intro Video</button>
                <a href="#how" class="px-8 py-4 bg-yellow-400 hover:bg-yellow-300 text-slate-900 rounded-full font-bold text-lg transition text-center md:inline-block">How Does It Work?</a>
            </div>
        </div>
    </div>
    <!-- Removed green circle overlay -->
</section>
<script>
// Hero slider logic
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.hero-dot');
    let current = 0;
    let timer = null;

    function showSlide(idx) {
        slides.forEach((slide, i) => {
            slide.classList.toggle('opacity-100', i === idx);
            slide.classList.toggle('opacity-0', i !== idx);
        });
        dots.forEach((dot, i) => {
            dot.classList.toggle('bg-white/70', i === idx);
            dot.classList.toggle('bg-white/40', i !== idx);
        });
        current = idx;
    }

    function nextSlide() {
        showSlide((current + 1) % slides.length);
    }
    function prevSlide() {
        showSlide((current - 1 + slides.length) % slides.length);
    }
    function startAuto() {
        timer = setInterval(nextSlide, 5000);
    }
    function stopAuto() {
        clearInterval(timer);
    }

    // Dots
    dots.forEach((dot, i) => {
        dot.addEventListener('click', () => {
            stopAuto();
            showSlide(i);
            startAuto();
        });
    });
    // Prev/Next
    document.getElementById('hero-prev').addEventListener('click', () => {
        stopAuto();
        prevSlide();
        startAuto();
    });
    document.getElementById('hero-next').addEventListener('click', () => {
        stopAuto();
        nextSlide();
        startAuto();
    });

    showSlide(0);
    startAuto();
});

// Smooth scroll with animation for anchor links
function smoothScrollTo(targetId) {
    const target = document.getElementById(targetId);
    if (!target) return;
    const yOffset = -40; // adjust for sticky nav if needed
    const y = target.getBoundingClientRect().top + window.pageYOffset + yOffset;
    window.scrollTo({ top: y, behavior: 'smooth' });
}
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href.startsWith('#') && href.length > 1) {
                const targetId = href.substring(1);
                if (document.getElementById(targetId)) {
                    e.preventDefault();
                    smoothScrollTo(targetId);
                }
            }
        });
    });
});
</script>
