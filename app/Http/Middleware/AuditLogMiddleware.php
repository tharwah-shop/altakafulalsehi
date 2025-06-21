<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Auth;

class AuditLogMiddleware
{
    /**
     * المسارات التي تحتاج تسجيل أمني
     */
    protected $securityRoutes = [
        'login',
        'logout',
        'password',
        'admin',
        'settings',
        'users',
        'roles',
        'permissions'
    ];

    /**
     * المسارات التي تحتاج تسجيل تجاري
     */
    protected $businessRoutes = [
        'subscribers',
        'medical-centers',
        'packages',
        'offers',
        'payments'
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        
        // تسجيل بداية الطلب
        $this->logRequestStart($request);
        
        $response = $next($request);
        
        $endTime = microtime(true);
        $duration = ($endTime - $startTime) * 1000; // بالميلي ثانية
        
        // تسجيل نهاية الطلب
        $this->logRequestEnd($request, $response, $duration);
        
        return $response;
    }

    /**
     * تسجيل بداية الطلب
     */
    protected function logRequestStart(Request $request)
    {
        $route = $request->route()?->getName() ?? $request->path();
        
        // تحديد نوع التسجيل المطلوب
        if ($this->isSecurityRoute($route)) {
            $this->logSecurityRequest($request, 'request_started');
        } elseif ($this->isBusinessRoute($route)) {
            $this->logBusinessRequest($request, 'request_started');
        }
    }

    /**
     * تسجيل نهاية الطلب
     */
    protected function logRequestEnd(Request $request, Response $response, $duration)
    {
        $route = $request->route()?->getName() ?? $request->path();
        $statusCode = $response->getStatusCode();
        
        $details = [
            'status_code' => $statusCode,
            'duration_ms' => round($duration, 2),
            'memory_usage' => $this->formatBytes(memory_get_peak_usage(true)),
            'response_size' => strlen($response->getContent())
        ];

        // تسجيل حسب نوع المسار
        if ($this->isSecurityRoute($route)) {
            $event = $statusCode >= 400 ? 'security_request_failed' : 'security_request_completed';
            $this->logSecurityRequest($request, $event, $details);
        } elseif ($this->isBusinessRoute($route)) {
            $event = $statusCode >= 400 ? 'business_request_failed' : 'business_request_completed';
            $this->logBusinessRequest($request, $event, $details);
        }

        // تسجيل الطلبات البطيئة
        if ($duration > 5000) { // أكثر من 5 ثواني
            $this->logSlowRequest($request, $duration, $details);
        }

        // تسجيل الأخطاء الحرجة
        if ($statusCode >= 500) {
            $this->logCriticalError($request, $response, $details);
        }
    }

    /**
     * تحديد ما إذا كان المسار أمني
     */
    protected function isSecurityRoute($route)
    {
        foreach ($this->securityRoutes as $securityRoute) {
            if (str_contains($route, $securityRoute)) {
                return true;
            }
        }
        return false;
    }

    /**
     * تحديد ما إذا كان المسار تجاري
     */
    protected function isBusinessRoute($route)
    {
        foreach ($this->businessRoutes as $businessRoute) {
            if (str_contains($route, $businessRoute)) {
                return true;
            }
        }
        return false;
    }

    /**
     * تسجيل طلب أمني
     */
    protected function logSecurityRequest(Request $request, $event, $details = [])
    {
        $requestDetails = array_merge([
            'route' => $request->route()?->getName() ?? $request->path(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'parameters' => $this->sanitizeParameters($request->all()),
            'headers' => $this->sanitizeHeaders($request->headers->all())
        ], $details);

        AuditLogService::logSecurityEvent($event, $requestDetails);
    }

    /**
     * تسجيل طلب تجاري
     */
    protected function logBusinessRequest(Request $request, $event, $details = [])
    {
        $requestDetails = array_merge([
            'route' => $request->route()?->getName() ?? $request->path(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'parameters' => $this->sanitizeParameters($request->all())
        ], $details);

        AuditLogService::logBusinessEvent($event, null, $request->method(), $requestDetails);
    }

    /**
     * تسجيل طلب بطيء
     */
    protected function logSlowRequest(Request $request, $duration, $details = [])
    {
        $slowRequestDetails = array_merge([
            'route' => $request->route()?->getName() ?? $request->path(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'duration_ms' => round($duration, 2),
            'threshold_exceeded' => true
        ], $details);

        AuditLogService::logSecurityEvent('slow_request_detected', $slowRequestDetails, 'warning');
    }

    /**
     * تسجيل خطأ حرج
     */
    protected function logCriticalError(Request $request, Response $response, $details = [])
    {
        $errorDetails = array_merge([
            'route' => $request->route()?->getName() ?? $request->path(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'response_content' => substr($response->getContent(), 0, 1000) // أول 1000 حرف فقط
        ], $details);

        AuditLogService::logCriticalError('http_server_error', $errorDetails);
    }

    /**
     * تنظيف المعاملات الحساسة
     */
    protected function sanitizeParameters($parameters)
    {
        $sensitiveFields = [
            'password',
            'password_confirmation',
            'current_password',
            'new_password',
            'token',
            'api_key',
            'secret',
            'credit_card',
            'cvv',
            'ssn',
            'id_number'
        ];

        foreach ($sensitiveFields as $field) {
            if (isset($parameters[$field])) {
                $parameters[$field] = '***REDACTED***';
            }
        }

        return $parameters;
    }

    /**
     * تنظيف الرؤوس الحساسة
     */
    protected function sanitizeHeaders($headers)
    {
        $sensitiveHeaders = [
            'authorization',
            'x-api-key',
            'x-auth-token',
            'cookie'
        ];

        foreach ($sensitiveHeaders as $header) {
            if (isset($headers[$header])) {
                $headers[$header] = ['***REDACTED***'];
            }
        }

        return $headers;
    }

    /**
     * تنسيق حجم الذاكرة
     */
    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
