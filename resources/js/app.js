import "./bootstrap";

function applyWlaTheme(theme) {
    var isDark = theme === 'dark';
    document.documentElement.classList.toggle('dark', isDark);
    try {
        localStorage.setItem('wla-theme', isDark ? 'dark' : 'light');
    } catch (e) {}
}

window.addEventListener('toggle-theme', function () {
    var next = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
    applyWlaTheme(next);
});

window.addEventListener('set-theme', function (event) {
    var theme = event.detail && event.detail.theme;
    if (theme === 'light' || theme === 'dark') {
        applyWlaTheme(theme);
    }
});

function setMobileSidebarOpen(open) {
    document.documentElement.classList.toggle('sidebar-mobile-open', open);
}

window.addEventListener('toggle-mobile-sidebar', function () {
    setMobileSidebarOpen(! document.documentElement.classList.contains('sidebar-mobile-open'));
});

window.addEventListener('close-mobile-sidebar', function () {
    setMobileSidebarOpen(false);
});

window.addEventListener('toggle-desktop-sidebar', function () {
    var hidden = ! document.documentElement.classList.contains('sidebar-desktop-hidden');
    document.documentElement.classList.toggle('sidebar-desktop-hidden', hidden);
    try {
        localStorage.setItem('wla-sidebar-hidden', hidden ? '1' : '0');
    } catch (e) {}
});

window.addEventListener('livewire:navigated', function () {
    setMobileSidebarOpen(false);
});
