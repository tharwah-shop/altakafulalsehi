<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class QueryOptimizationService
{
    /**
     * تحليل الاستعلامات البطيئة
     */
    public function analyzeSlowQueries()
    {
        $slowQueries = [];
        
        // تفعيل تسجيل الاستعلامات
        DB::enableQueryLog();
        
        // تحليل استعلامات المراكز الطبية الشائعة
        $slowQueries['medical_centers'] = $this->analyzeMedicalCenterQueries();
        
        // تحليل استعلامات المشتركين الشائعة
        $slowQueries['subscribers'] = $this->analyzeSubscriberQueries();
        
        // تحليل استعلامات العروض الشائعة
        $slowQueries['offers'] = $this->analyzeOfferQueries();
        
        // تحليل استعلامات المقالات الشائعة
        $slowQueries['posts'] = $this->analyzePostQueries();
        
        return $slowQueries;
    }

    /**
     * تحليل استعلامات المراكز الطبية
     */
    private function analyzeMedicalCenterQueries()
    {
        $queries = [];
        
        // استعلام البحث في المراكز الطبية
        $queries['search'] = [
            'description' => 'البحث في المراكز الطبية بالمدينة والنوع',
            'query' => 'SELECT * FROM medical_centers WHERE status = ? AND city = ? AND type = ?',
            'optimization' => 'استخدام فهرس مركب (status, city, type)',
            'index' => 'idx_medical_centers_search'
        ];
        
        // استعلام المراكز حسب التقييم
        $queries['by_rating'] = [
            'description' => 'ترتيب المراكز حسب التقييم',
            'query' => 'SELECT * FROM medical_centers WHERE status = "active" ORDER BY rating DESC, reviews_count DESC',
            'optimization' => 'استخدام فهرس مركب (rating, reviews_count)',
            'index' => 'idx_medical_centers_rating'
        ];
        
        // استعلام المراكز المضافة حديثاً
        $queries['recent'] = [
            'description' => 'المراكز المضافة حديثاً',
            'query' => 'SELECT * FROM medical_centers WHERE status = "active" ORDER BY created_at DESC',
            'optimization' => 'استخدام فهرس مركب (created_at, status)',
            'index' => 'idx_medical_centers_recent'
        ];
        
        return $queries;
    }

    /**
     * تحليل استعلامات المشتركين
     */
    private function analyzeSubscriberQueries()
    {
        $queries = [];
        
        // استعلام المشتركين المنتهية صلاحيتهم
        $queries['expired'] = [
            'description' => 'المشتركين المنتهية صلاحيتهم',
            'query' => 'SELECT * FROM subscribers WHERE status = "فعال" AND end_date < NOW()',
            'optimization' => 'استخدام فهرس مركب (status, end_date)',
            'index' => 'idx_subscribers_status_expiry'
        ];
        
        // استعلام المشتركين حسب الباقة
        $queries['by_package'] = [
            'description' => 'المشتركين حسب الباقة والحالة',
            'query' => 'SELECT * FROM subscribers WHERE package_id = ? AND status = ? ORDER BY start_date DESC',
            'optimization' => 'استخدام فهرس مركب (package_id, status, start_date)',
            'index' => 'idx_subscribers_package'
        ];
        
        // استعلام الإحصائيات الديموغرافية
        $queries['demographics'] = [
            'description' => 'إحصائيات المشتركين حسب الجنسية والمدينة',
            'query' => 'SELECT nationality, city, COUNT(*) FROM subscribers GROUP BY nationality, city',
            'optimization' => 'استخدام فهرس مركب (nationality, city)',
            'index' => 'idx_subscribers_demographics'
        ];
        
        return $queries;
    }

    /**
     * تحليل استعلامات العروض
     */
    private function analyzeOfferQueries()
    {
        $queries = [];
        
        // استعلام العروض النشطة
        $queries['active'] = [
            'description' => 'العروض النشطة في الفترة الحالية',
            'query' => 'SELECT * FROM offers WHERE status = "active" AND start_date <= NOW() AND end_date >= NOW()',
            'optimization' => 'استخدام فهرس مركب (status, start_date, end_date)',
            'index' => 'idx_offers_active'
        ];
        
        // استعلام العروض المميزة
        $queries['featured'] = [
            'description' => 'العروض المميزة النشطة',
            'query' => 'SELECT * FROM offers WHERE is_featured = 1 AND status = "active" ORDER BY start_date DESC',
            'optimization' => 'استخدام فهرس مركب (is_featured, status, start_date)',
            'index' => 'idx_offers_featured'
        ];
        
        // استعلام عروض مركز طبي محدد
        $queries['by_center'] = [
            'description' => 'عروض مركز طبي محدد',
            'query' => 'SELECT * FROM offers WHERE medical_center_id = ? AND status = "active"',
            'optimization' => 'استخدام فهرس مركب (medical_center_id, status)',
            'index' => 'idx_offers_center'
        ];
        
        return $queries;
    }

    /**
     * تحليل استعلامات المقالات
     */
    private function analyzePostQueries()
    {
        $queries = [];
        
        // استعلام المقالات المنشورة
        $queries['published'] = [
            'description' => 'المقالات المنشورة',
            'query' => 'SELECT * FROM posts WHERE status = "published" ORDER BY published_at DESC',
            'optimization' => 'استخدام فهرس مركب (status, published_at)',
            'index' => 'idx_posts_published'
        ];
        
        // استعلام المقالات حسب الفئة
        $queries['by_category'] = [
            'description' => 'المقالات حسب الفئة',
            'query' => 'SELECT * FROM posts WHERE category_id = ? AND status = "published" AND is_featured = 1',
            'optimization' => 'استخدام فهرس مركب (category_id, status, is_featured)',
            'index' => 'idx_posts_category'
        ];
        
        // استعلام مقالات مركز طبي
        $queries['by_center'] = [
            'description' => 'مقالات مركز طبي محدد',
            'query' => 'SELECT * FROM posts WHERE medical_center_id = ? AND status = "published"',
            'optimization' => 'استخدام فهرس مركب (medical_center_id, status)',
            'index' => 'idx_posts_center'
        ];
        
        return $queries;
    }

    /**
     * تحسين استعلام محدد
     */
    public function optimizeQuery($table, $queryType)
    {
        $optimizations = [
            'medical_centers' => [
                'search' => 'WHERE status = ? AND city = ? AND type = ?',
                'rating' => 'WHERE status = "active" ORDER BY rating DESC, reviews_count DESC LIMIT 10',
                'recent' => 'WHERE status = "active" ORDER BY created_at DESC LIMIT 20'
            ],
            'subscribers' => [
                'expired' => 'WHERE status = "فعال" AND end_date < CURDATE()',
                'active' => 'WHERE status = "فعال" AND end_date >= CURDATE()',
                'by_package' => 'WHERE package_id = ? AND status = ?'
            ],
            'offers' => [
                'active' => 'WHERE status = "active" AND start_date <= CURDATE() AND end_date >= CURDATE()',
                'featured' => 'WHERE is_featured = 1 AND status = "active"',
                'expired' => 'WHERE end_date < CURDATE() OR status = "expired"'
            ]
        ];

        return $optimizations[$table][$queryType] ?? null;
    }

    /**
     * تسجيل تحليل الأداء
     */
    public function logPerformanceAnalysis()
    {
        $analysis = $this->analyzeSlowQueries();
        
        Log::channel('performance')->info('Query Performance Analysis', [
            'timestamp' => now(),
            'analysis' => $analysis,
            'recommendations' => $this->getOptimizationRecommendations()
        ]);
        
        return $analysis;
    }

    /**
     * الحصول على توصيات التحسين
     */
    private function getOptimizationRecommendations()
    {
        return [
            'indexes' => 'تم إضافة فهارس مركبة لتحسين الأداء',
            'caching' => 'استخدام التخزين المؤقت للاستعلامات المتكررة',
            'pagination' => 'استخدام التقسيم للنتائج الكبيرة',
            'eager_loading' => 'استخدام Eager Loading لتجنب N+1 queries',
            'query_optimization' => 'تحسين الاستعلامات المعقدة'
        ];
    }

    /**
     * تنظيف الكاش
     */
    public function clearQueryCache()
    {
        Cache::tags(['queries', 'performance'])->flush();
        return true;
    }
}
