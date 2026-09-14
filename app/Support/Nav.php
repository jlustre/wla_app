<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;

class Nav
{
    public static function route(?string $name, mixed $parameters = [], bool $absolute = false): string
    {
        if (! is_string($name) || $name === '' || ! Route::has($name)) {
            return '#';
        }

        return route($name, $parameters, $absolute);
    }

    public static function localHref(mixed $href): string
    {
        if (! is_string($href) || $href === '' || $href === '#') {
            return is_string($href) && $href !== '' ? $href : '#';
        }

        if (! preg_match('#^https?://#i', $href)) {
            return $href;
        }

        $parts = parse_url($href);
        $hrefHost = $parts['host'] ?? null;
        $currentHost = request()->getHost();
        $configuredHost = parse_url((string) config('app.url'), PHP_URL_HOST);

        $sameApp = $hrefHost && in_array($hrefHost, array_values(array_unique(array_filter([
            $currentHost,
            $configuredHost,
        ]))), true);

        if (! $sameApp) {
            return $href;
        }

        return ($parts['path'] ?? '/')
            .(isset($parts['query']) ? '?'.$parts['query'] : '')
            .(isset($parts['fragment']) ? '#'.$parts['fragment'] : '');
    }

    public static function isExternalHref(mixed $href): bool
    {
        if (! is_string($href) || ! preg_match('#^https?://#i', $href)) {
            return false;
        }

        $hrefHost = parse_url($href, PHP_URL_HOST);
        $currentHost = request()->getHost();
        $configuredHost = parse_url((string) config('app.url'), PHP_URL_HOST);

        return $hrefHost && ! in_array($hrefHost, array_values(array_unique(array_filter([
            $currentHost,
            $configuredHost,
        ]))), true);
    }
}
