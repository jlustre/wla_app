<button
    x-data="{ show: false }"
    x-init="window.addEventListener('scroll', () => { show = window.scrollY > 200 })"
    x-show="show"
    @click="window.scrollTo({top: 0, behavior: 'smooth'})"
    class="fixed bottom-6 right-6 z-50 rounded-full bg-blue-600 text-white shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-opacity p-3"
    style="display: none"
    x-transition.opacity
    aria-label="Go to top"
>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        class="h-6 w-6"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
        stroke-width="2"
    >
        <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M5 15l7-7 7 7"
        />
    </svg>
</button>
