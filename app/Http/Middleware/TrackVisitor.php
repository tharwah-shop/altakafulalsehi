<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Stevebauman\Location\Facades\Location;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Log;

class TrackVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // تتبع الزوار فقط للصفحات المحددة
        $trackableRoutes = [
            'card-request',
            'subscribe',
            'medical-network',
            'home'
        ];

        $currentRoute = $request->route() ? $request->route()->getName() : '';

        if (in_array($currentRoute, $trackableRoutes) || $request->is('/')) {
            $this->trackVisitor($request);
        }

        return $next($request);
    }

    /**
     * Track visitor information
     */
    private function trackVisitor(Request $request)
    {
        try {
            $ip = $request->ip();
            $userAgent = $request->userAgent();

            // تجنب تتبع البوتات والكرولرز
            if ($this->isBot($userAgent)) {
                return;
            }

            // الحصول على معلومات الموقع
            $location = Location::get($ip);

            // تحديد نوع الجهاز
            $agent = new Agent();
            $deviceType = 'desktop';
            if ($agent->isMobile()) {
                $deviceType = 'mobile';
            } elseif ($agent->isTablet()) {
                $deviceType = 'tablet';
            }

            // تحديد مصدر الزيارة
            $source = $this->determineSource($request);

            // حفظ معلومات الزائر في session لاستخدامها لاحقاً
            session([
                'visitor_tracking' => [
                    'ip_address' => $ip,
                    'user_agent' => $userAgent,
                    'device_type' => $deviceType,
                    'source' => $source,
                    'referrer_url' => $request->headers->get('referer'),
                    'landing_page' => $request->fullUrl(),
                    'utm_source' => $request->get('utm_source'),
                    'utm_medium' => $request->get('utm_medium'),
                    'utm_campaign' => $request->get('utm_campaign'),
                    'utm_term' => $request->get('utm_term'),
                    'utm_content' => $request->get('utm_content'),
                    'location' => $location ? [
                        'country' => $location->countryName,
                        'city' => $location->cityName,
                        'region' => $location->regionName,
                    ] : null,
                ]
            ]);

        } catch (\Exception $e) {
            // تسجيل الخطأ دون إيقاف التطبيق
            Log::error('Visitor tracking error: ' . $e->getMessage());
        }
    }

    /**
     * Determine the traffic source
     */
    private function determineSource(Request $request)
    {
        $referer = $request->headers->get('referer');
        $utmSource = $request->get('utm_source');

        if ($utmSource) {
            return $utmSource;
        }

        if (!$referer) {
            return 'direct';
        }

        $refererHost = parse_url($referer, PHP_URL_HOST);
        $currentHost = $request->getHost();

        if ($refererHost === $currentHost) {
            return 'internal';
        }

        // تحديد المصدر بناءً على الرابط المرجعي
        if (strpos($refererHost, 'google') !== false) {
            return 'google_organic';
        } elseif (strpos($refererHost, 'facebook') !== false) {
            return 'facebook';
        } elseif (strpos($refererHost, 'twitter') !== false) {
            return 'twitter';
        } elseif (strpos($refererHost, 'instagram') !== false) {
            return 'instagram';
        } elseif (strpos($refererHost, 'youtube') !== false) {
            return 'youtube';
        } elseif (strpos($refererHost, 'linkedin') !== false) {
            return 'linkedin';
        }

        return 'referral';
    }

    /**
     * Check if the user agent is a bot
     */
    private function isBot($userAgent)
    {
        $bots = [
            'googlebot', 'bingbot', 'slurp', 'duckduckbot', 'baiduspider',
            'yandexbot', 'facebookexternalhit', 'twitterbot', 'rogerbot',
            'linkedinbot', 'embedly', 'quora link preview', 'showyoubot',
            'outbrain', 'pinterest', 'developers.google.com/+/web/snippet'
        ];

        foreach ($bots as $bot) {
            if (stripos($userAgent, $bot) !== false) {
                return true;
            }
        }

        return false;
    }
}
