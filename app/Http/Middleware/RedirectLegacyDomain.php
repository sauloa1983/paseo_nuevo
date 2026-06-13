<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectLegacyDomain
{
    /** @var list<string> */
    private const LEGACY_HOSTS = [
        'paseoespanainmobiliaria.com',
        'www.paseoespanainmobiliaria.com',
    ];

    /** @var list<string> */
    private const SITE_HOSTS = [
        'paseoespana.com',
        'www.paseoespana.com',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $host = strtolower($request->getHost());
        $canonicalHost = $this->canonicalHost();

        if ($canonicalHost === null) {
            return $next($request);
        }

        $isLegacy = in_array($host, self::LEGACY_HOSTS, true);
        $isSiteHost = in_array($host, self::SITE_HOSTS, true);

        if (! $isLegacy && ! $isSiteHost) {
            return $next($request);
        }

        if ($isLegacy || $host !== $canonicalHost || ! $this->isHttps($request)) {
            $target = 'https://'.$canonicalHost.$request->getRequestUri();

            return redirect()->away($target, 301);
        }

        return $next($request);
    }

    private function canonicalHost(): ?string
    {
        $appUrl = (string) config('app.url');
        $host = parse_url($appUrl, PHP_URL_HOST);

        return is_string($host) && $host !== '' ? strtolower($host) : null;
    }

    private function isHttps(Request $request): bool
    {
        if ($request->isSecure()) {
            return true;
        }

        return strtolower((string) $request->header('X-Forwarded-Proto', '')) === 'https';
    }
}
