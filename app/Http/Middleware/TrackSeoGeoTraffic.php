<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\PageVisit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TrackSeoGeoTraffic
{
    private const AI_BOTS = [
        'GPTBot' => 'GPTBot (OpenAI)',
        'OAI-SearchBot' => 'OAI-SearchBot (ChatGPT)',
        'ChatGPT-User' => 'ChatGPT-User',
        'PerplexityBot' => 'PerplexityBot',
        'Perplexity-User' => 'Perplexity-User',
        'ClaudeBot' => 'ClaudeBot (Anthropic)',
        'Claude-Web' => 'Claude-Web',
        'anthropic-ai' => 'Anthropic AI',
        'Google-Extended' => 'Google-Extended (Gemini)',
        'Applebot-Extended' => 'Applebot-Extended',
        'Bytespider' => 'Bytespider',
        'CCBot' => 'CCBot',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->shouldTrack($request, $response)) {
            $this->recordVisit($request);
        }

        return $response;
    }

    private function shouldTrack(Request $request, Response $response): bool
    {
        if (! $request->isMethod('GET') || $response->getStatusCode() >= 400) {
            return false;
        }

        if ($request->is(
            'admin*',
            'dashboard*',
            'clients*',
            'projects*',
            'invoices*',
            'billing*',
            'bank-accounts*',
            'onboarding*',
            'profile*',
            'i/*',
            'api*',
            'build*',
            'storage*',
            'up',
            'sitemap.xml',
            'llms.txt',
            'robots.txt',
            'login',
            'register',
            'forgot-password',
            'reset-password*',
            'verify-email*',
            'auth/*'
        )) {
            return false;
        }

        $path = $request->path();
        if (preg_match('/\.(css|js|png|jpg|jpeg|gif|svg|ico|webp|woff2?|map)$/i', $path)) {
            return false;
        }

        return true;
    }

    private function recordVisit(Request $request): void
    {
        try {
            $ua = (string) $request->userAgent();
            $referer = (string) $request->headers->get('referer', '');
            $referrerHost = $referer ? (parse_url($referer, PHP_URL_HOST) ?: null) : null;

            [$visitorType, $botName] = $this->detectVisitor($ua);
            $trafficSource = $this->detectTrafficSource($visitorType, $referrerHost, $request);

            $path = '/' . ltrim($request->path(), '/');
            if ($path !== '/') {
                $path = rtrim($path, '/');
            }

            PageVisit::create([
                'path' => Str::limit($path, 255, ''),
                'route_name' => $request->route()?->getName(),
                'referrer_host' => $referrerHost ? Str::limit($referrerHost, 150, '') : null,
                'traffic_source' => $trafficSource,
                'visitor_type' => $visitorType,
                'bot_name' => $botName,
                'user_agent' => Str::limit($ua, 500, ''),
                'ip_hash' => hash('sha256', ($request->ip() ?? '0.0.0.0') . config('app.key')),
                'created_at' => now(),
            ]);
        } catch (\Throwable) {
            // Never block page response if telemetry fails
        }
    }

    /**
     * @return array{0: string, 1: ?string}
     */
    private function detectVisitor(string $ua): array
    {
        foreach (self::AI_BOTS as $signature => $label) {
            if (stripos($ua, $signature) !== false) {
                return ['aibot', $label];
            }
        }

        if (stripos($ua, 'Googlebot') !== false || stripos($ua, 'AdsBot-Google') !== false) {
            return ['googlebot', 'Googlebot'];
        }

        if (stripos($ua, 'facebookexternalhit') !== false || stripos($ua, 'meta-externalagent') !== false || stripos($ua, 'Facebot') !== false) {
            return ['metabot', 'Meta Crawler'];
        }

        if (preg_match('/(bingbot|yandex|duckduckbot|slurp|baiduspider|semrush|ahrefs|bot|crawler|spider)/i', $ua)) {
            return ['other_bot', 'Search/SEO Bot'];
        }

        return ['human', null];
    }

    private function detectTrafficSource(string $visitorType, ?string $referrerHost, Request $request): string
    {
        if ($visitorType === 'aibot') {
            return 'ai_search';
        }
        if ($visitorType === 'googlebot') {
            return 'google';
        }
        if ($visitorType === 'metabot') {
            return 'meta';
        }

        $utmSource = strtolower((string) $request->query('utm_source', ''));
        if (str_contains($utmSource, 'google') || $request->has('gclid')) {
            return 'google';
        }
        if (str_contains($utmSource, 'facebook') || str_contains($utmSource, 'instagram') || str_contains($utmSource, 'meta') || $request->has('fbclid')) {
            return 'meta';
        }
        if (str_contains($utmSource, 'chatgpt') || str_contains($utmSource, 'perplexity') || str_contains($utmSource, 'claude') || str_contains($utmSource, 'gemini')) {
            return 'ai_search';
        }

        if ($referrerHost) {
            $host = strtolower($referrerHost);
            if (str_contains($host, 'google.')) {
                return 'google';
            }
            if (str_contains($host, 'facebook.com') || str_contains($host, 'fb.com') || str_contains($host, 'instagram.com') || str_contains($host, 'threads.net')) {
                return 'meta';
            }
            if (str_contains($host, 'chatgpt.com') || str_contains($host, 'openai.com') || str_contains($host, 'perplexity.ai') || str_contains($host, 'claude.ai') || str_contains($host, 'gemini.google.com') || str_contains($host, 'copilot.microsoft.com')) {
                return 'ai_search';
            }
            if (! str_contains($host, 'invoicerapi.synbarra.com')) {
                return 'referral';
            }
        }

        return 'direct';
    }
}
