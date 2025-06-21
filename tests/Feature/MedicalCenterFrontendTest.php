<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\MedicalCenter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MedicalCenterFrontendTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // إنشاء قرص تخزين وهمي للاختبار
        Storage::fake('public');
    }

    /** @test */
    public function medical_centers_index_page_displays_correctly()
    {
        // إنشاء مراكز طبية مع صور
        $centerWithImage = MedicalCenter::factory()->create([
            'name' => 'مركز طبي مع صورة',
            'image' => 'medical-centers/center_with_image.jpg',
            'status' => 'active'
        ]);
        
        $centerWithoutImage = MedicalCenter::factory()->create([
            'name' => 'مركز طبي بدون صورة',
            'image' => null,
            'status' => 'active'
        ]);
        
        // رفع صورة وهمية للمركز الأول
        $image = UploadedFile::fake()->image('test.jpg', 800, 600);
        Storage::disk('public')->put($centerWithImage->image, $image->getContent());
        
        // رفع صورة مصغرة وهمية
        $thumbnailPath = 'medical-centers/thumb_center_with_image.jpg';
        Storage::disk('public')->put($thumbnailPath, $image->getContent());
        
        $response = $this->get(route('medical-centers.index'));
        
        $response->assertStatus(200);
        $response->assertSee('مركز طبي مع صورة');
        $response->assertSee('مركز طبي بدون صورة');
        
        // التحقق من عرض الصورة للمركز الأول
        $response->assertSee($centerWithImage->image_url);
        
        // التحقق من عرض أيقونة افتراضية للمركز الثاني
        $response->assertSee('bi-hospital');
    }

    /** @test */
    public function medical_center_detail_page_displays_image_correctly()
    {
        $center = MedicalCenter::factory()->create([
            'name' => 'مركز طبي تفصيلي',
            'image' => 'medical-centers/detail_center.jpg',
            'status' => 'active'
        ]);
        
        // رفع صورة وهمية
        $image = UploadedFile::fake()->image('detail.jpg', 800, 600);
        Storage::disk('public')->put($center->image, $image->getContent());
        
        $response = $this->get(route('medical-center.show', $center->slug));
        
        $response->assertStatus(200);
        $response->assertSee($center->name);
        $response->assertSee($center->image_url);
        
        // التحقق من وجود شارة التحقق للمراكز النشطة
        $response->assertSee('bi-check');
    }

    /** @test */
    public function medical_center_detail_page_handles_missing_image()
    {
        $center = MedicalCenter::factory()->create([
            'name' => 'مركز بدون صورة',
            'image' => null,
            'status' => 'active'
        ]);
        
        $response = $this->get(route('medical-center.show', $center->slug));
        
        $response->assertStatus(200);
        $response->assertSee($center->name);
        
        // التحقق من عرض أيقونة افتراضية
        $response->assertSee('bi-hospital');
    }

    /** @test */
    public function medical_centers_index_shows_thumbnail_when_available()
    {
        $center = MedicalCenter::factory()->create([
            'name' => 'مركز مع صورة مصغرة',
            'image' => 'medical-centers/thumb_test.jpg',
            'status' => 'active'
        ]);
        
        // رفع صورة أصلية
        $originalImage = UploadedFile::fake()->image('original.jpg', 800, 600);
        Storage::disk('public')->put($center->image, $originalImage->getContent());
        
        // رفع صورة مصغرة
        $thumbnailPath = 'medical-centers/thumb_thumb_test.jpg';
        $thumbnailImage = UploadedFile::fake()->image('thumbnail.jpg', 300, 300);
        Storage::disk('public')->put($thumbnailPath, $thumbnailImage->getContent());
        
        $response = $this->get(route('medical-centers.index'));
        
        $response->assertStatus(200);
        
        // يجب أن تظهر الصورة المصغرة في القائمة
        $response->assertSee($center->thumbnail_url ?? $center->image_url);
    }

    /** @test */
    public function medical_center_cards_show_proper_badges()
    {
        $center = MedicalCenter::factory()->create([
            'name' => 'مركز مع شارات',
            'type' => '1', // مستشفى عام
            'status' => 'active',
            'medical_discounts' => [
                ['service' => 'كشف عام', 'discount' => '20%'],
                ['service' => 'تحاليل', 'discount' => '15%']
            ]
        ]);
        
        $response = $this->get(route('medical-centers.index'));
        
        $response->assertStatus(200);
        
        // التحقق من عرض نوع المركز
        $response->assertSee('مستشفى عام');
        
        // التحقق من عرض شارة النشاط
        $response->assertSee('نشط');
    }

    /** @test */
    public function medical_center_image_has_hover_effect()
    {
        $center = MedicalCenter::factory()->create([
            'image' => 'medical-centers/hover_test.jpg',
            'status' => 'active'
        ]);
        
        // رفع صورة وهمية
        $image = UploadedFile::fake()->image('hover.jpg', 800, 600);
        Storage::disk('public')->put($center->image, $image->getContent());
        
        $response = $this->get(route('medical-centers.index'));
        
        $response->assertStatus(200);
        
        // التحقق من وجود تأثير التحويم
        $response->assertSee('onmouseover');
        $response->assertSee('transform');
    }

    /** @test */
    public function medical_center_image_has_error_fallback()
    {
        $center = MedicalCenter::factory()->create([
            'image' => 'medical-centers/non_existent.jpg',
            'status' => 'active'
        ]);
        
        $response = $this->get(route('medical-centers.index'));
        
        $response->assertStatus(200);
        
        // التحقق من وجود معالج الأخطاء
        $response->assertSee('onerror');
        $response->assertSee('nextElementSibling');
    }

    /** @test */
    public function medical_centers_search_works_with_images()
    {
        $center1 = MedicalCenter::factory()->create([
            'name' => 'مركز الرياض الطبي',
            'city' => 'الرياض',
            'image' => 'medical-centers/riyadh.jpg',
            'status' => 'active'
        ]);
        
        $center2 = MedicalCenter::factory()->create([
            'name' => 'مركز جدة الطبي',
            'city' => 'جدة',
            'image' => null,
            'status' => 'active'
        ]);
        
        // البحث عن مراكز الرياض
        $response = $this->get(route('medical-centers.index', ['search' => 'الرياض']));
        
        $response->assertStatus(200);
        $response->assertSee('مركز الرياض الطبي');
        $response->assertDontSee('مركز جدة الطبي');
    }

    /** @test */
    public function medical_center_contact_buttons_work()
    {
        $center = MedicalCenter::factory()->create([
            'name' => 'مركز مع جهات اتصال',
            'phone' => '966501234567',
            'website' => 'https://example.com',
            'status' => 'active'
        ]);
        
        $response = $this->get(route('medical-centers.index'));
        
        $response->assertStatus(200);
        
        // التحقق من وجود رابط الهاتف
        $response->assertSee('tel:' . $center->phone);
        
        // التحقق من وجود رابط الموقع
        $response->assertSee($center->website);
        $response->assertSee('target="_blank"');
    }

    /** @test */
    public function medical_center_rating_displays_correctly()
    {
        $center = MedicalCenter::factory()->create([
            'name' => 'مركز مع تقييم',
            'rating' => 4.5,
            'reviews_count' => 25,
            'status' => 'active'
        ]);
        
        $response = $this->get(route('medical-centers.index'));
        
        $response->assertStatus(200);
        
        // التحقق من عرض النجوم
        $response->assertSee('bi-star-fill');
        $response->assertSee('bi-star-half');
        
        // التحقق من عرض عدد التقييمات
        $response->assertSee('(25)');
    }
}
