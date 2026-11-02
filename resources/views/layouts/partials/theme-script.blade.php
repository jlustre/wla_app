@php
    $wlaProfileTheme = auth()->user()?->profile?->theme_preference;
@endphp
<script>
    (function () {
        try {
            var stored = localStorage.getItem('wla-theme');
            var profileTheme = @json($wlaProfileTheme);
            var theme = (stored === 'light' || stored === 'dark')
                ? stored
                : ((profileTheme === 'light' || profileTheme === 'dark')
                    ? profileTheme
                    : ((window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light'));

            document.documentElement.classList.toggle('dark', theme === 'dark');
        } catch (e) {}

        try {
            document.documentElement.classList.toggle('sidebar-desktop-hidden', localStorage.getItem('wla-sidebar-hidden') === '1');
        } catch (e) {}

        // Strip a deep-link #hash before the browser can auto-jump to it, so
        // landing pages with scroll-reveal animations can replay that jump as
        // their own smooth, animated scroll once the page is ready (see
        // handleInitialHashScroll in app.js) instead of a silent instant jump.
        try {
            if (location.hash && location.hash.length > 1) {
                window.__wlaInitialHash = location.hash;
                history.replaceState(history.state, '', location.pathname + location.search);
            }
        } catch (e) {}
    })();
</script>
