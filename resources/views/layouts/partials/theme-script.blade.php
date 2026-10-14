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
    })();
</script>
