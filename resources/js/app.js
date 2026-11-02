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

function initScrollReveal() {
    var targets = document.querySelectorAll('.reveal, .reveal-group');
    if (!targets.length) return;

    if (!('IntersectionObserver' in window)) {
        targets.forEach(function (el) { el.classList.add('in-view'); });
        return;
    }

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('in-view');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15, rootMargin: '0px 0px -10% 0px' });

    targets.forEach(function (el) { observer.observe(el); });
}

// A deep link (or refresh) landing mid-page onto a #anchor would otherwise
// have the browser jump there instantly before any script runs, so the
// section's hidden reveal state never gets painted and it just appears with
// nothing to animate from. theme-script.blade.php strips the hash before the
// browser can act on it and stashes it on window.__wlaInitialHash; here we
// replay it as the same smooth scroll a nav click would do, so sections
// animate in as they're passed — same as scrolling there by hand.
//
// Chrome can still perform its own scroll-to-fragment once around the `load`
// event (once images/layout settle) even though the hash was stripped early,
// so `onReady` (which wires up the reveal observer) isn't called until after
// `load`, once we've forced the scroll position back and let that settle —
// otherwise the observer could see the stray jump and mark sections in-view
// before our own animated scroll ever runs.
function handleInitialHashScroll(onReady) {
    var hash = window.__wlaInitialHash;
    var target = hash && hash.length > 1 ? document.getElementById(hash.substring(1)) : null;
    if (!target) {
        onReady();
        return;
    }

    history.replaceState(history.state, '', hash);

    function settle() {
        window.scrollTo(0, 0);
        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                onReady();
                window.setTimeout(function () {
                    var yOffset = -40;
                    var y = target.getBoundingClientRect().top + window.pageYOffset + yOffset;
                    window.scrollTo({ top: Math.max(y, 0), behavior: 'smooth' });
                }, 50);
            });
        });
    }

    if (document.readyState === 'complete') {
        settle();
    } else {
        window.addEventListener('load', settle, { once: true });
    }
}

document.addEventListener('DOMContentLoaded', function () {
    handleInitialHashScroll(initScrollReveal);
});
document.addEventListener('livewire:navigated', initScrollReveal);
