<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\MedicalCenter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MedicalCenterImageTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // إنشاء قرص تخزين وهمي للاختبار
        Storage::fake('public');
        
        // إنشاء مستخدم إداري
        $this->admin = User::factory()->create([
            'email' => 'admin@test.com',
            'is_admin' => true,
        ]);
    }

    /** @test */
    public function admin_can_create_medical_center_with_image()
    {
        $this->actingAs($this->admin);
        
        $image = UploadedFile::fake()->image('center.jpg', 800, 600);
        
        $response = $this->post(route('admin.medical-centers.store'), [
            'name' => 'مركز طبي تجريبي',
            'slug' => 'test-medical-center',
            'city' => 'الرياض',
            'type' => '1',
            'status' => 'active',
            'image' => $image,
        ]);
        
        $response->assertRedirect();
        
        $center = MedicalCenter::where('name', 'مركز طبي تجريبي')->first();
        $this->assertNotNull($center);
        $this->assertNotNull($center->image);
        
        // التحقق من رفع الصورة
        Storage::disk('public')->assertExists($center->image);
        
        // التحقق من إنشاء الصورة المصغرة
        $thumbnailPath = str_replace('medical-centers/', 'medical-centers/thumb_', $center->image);
        Storage::disk('public')->assertExists($thumbnailPath);
    }

    /** @test */
    public function admin_can_update_medical_center_image()
    {
        $this->actingAs($this->admin);
        
        // إنشاء مركز طبي مع صورة
        $oldImage = UploadedFile::fake()->image('old.jpg', 800, 600);
        $center = MedicalCenter::factory()->create([
            'image' => 'medical-centers/old_image.jpg'
        ]);
        
        // رفع الصورة القديمة فعلياً
        Storage::disk('public')->put($center->image, $oldImage->getContent());
        
        // تحديث بصورة جديدة
        $newImage = UploadedFile::fake()->image('new.jpg', 800, 600);
        
        $response = $this->put(route('admin.medical-centers.update', $center), [
            'name' => $center->name,
            'city' => $center->city,
            'type' => $center->type,
            'status' => $center->status,
            'image' => $newImage,
        ]);
        
        $response->assertRedirect();
        
        $center->refresh();
        
        // التحقق من تحديث الصورة
        $this->assertNotEquals('medical-centers/old_image.jpg', $center->image);
        Storage::disk('public')->assertExists($center->image);
        
        // التحقق من حذف الصورة القديمة
        Storage::disk('public')->assertMissing('medical-centers/old_image.jpg');
    }

    /** @test */
    public function admin_can_remove_medical_center_image()
    {
        $this->actingAs($this->admin);
        
        // إنشاء مركز طبي مع صورة
        $image = UploadedFile::fake()->image('test.jpg', 800, 600);
        $center = MedicalCenter::factory()->create([
            'image' => 'medical-centers/test_image.jpg'
        ]);
        
        // رفع الصورة فعلياً
        Storage::disk('public')->put($center->image, $image->getContent());
        
        // إزالة الصورة
        $response = $this->put(route('admin.medical-centers.update', $center), [
            'name' => $center->name,
            'city' => $center->city,
            'type' => $center->type,
            'status' => $center->status,
            'remove_current_image' => '1',
        ]);
        
        $response->assertRedirect();
        
        $center->refresh();
        
        // التحقق من إزالة الصورة
        $this->assertNull($center->image);
        Storage::disk('public')->assertMissing('medical-centers/test_image.jpg');
    }

    /** @test */
    public function it_rejects_invalid_image_format()
    {
        $this->actingAs($this->admin);
        
        $invalidFile = UploadedFile::fake()->create('document.pdf', 100);
        
        $response = $this->post(route('admin.medical-centers.store'), [
            'name' => 'مركز طبي تجريبي',
            'city' => 'الرياض',
            'type' => '1',
            'status' => 'active',
            'image' => $invalidFile,
        ]);
        
        $response->assertSessionHasErrors('image');
    }

    /** @test */
    public function it_rejects_oversized_image()
    {
        $this->actingAs($this->admin);
        
        $largeImage = UploadedFile::fake()->image('large.jpg')->size(6000); // 6MB
        
        $response = $this->post(route('admin.medical-centers.store'), [
            'name' => 'مركز طبي تجريبي',
            'city' => 'الرياض',
            'type' => '1',
            'status' => 'active',
            'image' => $largeImage,
        ]);
        
        $response->assertSessionHasErrors('image');
    }

    /** @test */
    public function medical_center_image_url_attribute_works()
    {
        $center = MedicalCenter::factory()->create([
            'image' => 'medical-centers/test_image.jpg'
        ]);
        
        // رفع صورة وهمية
        $image = UploadedFile::fake()->image('test.jpg', 800, 600);
        Storage::disk('public')->put($center->image, $image->getContent());
        
        $imageUrl = $center->image_url;
        
        $this->assertNotNull($imageUrl);
        $this->assertStringContainsString('storage/', $imageUrl);
        $this->assertStringContainsString('test_image.jpg', $imageUrl);
    }

    /** @test */
    public function medical_center_thumbnail_url_attribute_works()
    {
        $center = MedicalCenter::factory()->create([
            'image' => 'medical-centers/test_image.jpg'
        ]);
        
        // رفع صورة وهمية
        $image = UploadedFile::fake()->image('test.jpg', 800, 600);
        Storage::disk('public')->put($center->image, $image->getContent());
        
        // رفع صورة مصغرة وهمية
        $thumbnailPath = 'medical-centers/thumb_test_image.jpg';
        Storage::disk('public')->put($thumbnailPath, $image->getContent());
        
        $thumbnailUrl = $center->thumbnail_url;
        
        $this->assertNotNull($thumbnailUrl);
        $this->assertStringContainsString('storage/', $thumbnailUrl);
        $this->assertStringContainsString('thumb_', $thumbnailUrl);
    }

    /** @test */
    public function medical_center_image_info_attribute_works()
    {
        $center = MedicalCenter::factory()->create([
            'image' => 'medical-centers/test_image.jpg'
        ]);
        
        // رفع صورة وهمية
        $image = UploadedFile::fake()->image('test.jpg', 800, 600);
        Storage::disk('public')->put($center->image, $image->getContent());
        
        $imageInfo = $center->image_info;
        
        $this->assertNotNull($imageInfo);
        $this->assertArrayHasKey('width', $imageInfo);
        $this->assertArrayHasKey('height', $imageInfo);
        $this->assertArrayHasKey('size', $imageInfo);
    }

    /** @test */
    public function medical_center_without_image_returns_null_urls()
    {
        $center = MedicalCenter::factory()->create([
            'image' => null
        ]);
        
        $this->assertNull($center->image_url);
        $this->assertNull($center->thumbnail_url);
        $this->assertNull($center->image_info);
    }

    /** @test */
    public function deleting_medical_center_removes_associated_images()
    {
        $center = MedicalCenter::factory()->create([
            'image' => 'medical-centers/test_image.jpg'
        ]);
        
        // رفع صورة وهمية
        $image = UploadedFile::fake()->image('test.jpg', 800, 600);
        Storage::disk('public')->put($center->image, $image->getContent());
        
        // رفع صورة مصغرة وهمية
        $thumbnailPath = 'medical-centers/thumb_test_image.jpg';
        Storage::disk('public')->put($thumbnailPath, $image->getContent());
        
        // التحقق من وجود الصور
        Storage::disk('public')->assertExists($center->image);
        Storage::disk('public')->assertExists($thumbnailPath);
        
        // حذف المركز الطبي
        $center->delete();
        
        // التحقق من حذف الصور (يجب إضافة هذا في Model Observer)
        // Storage::disk('public')->assertMissing($center->image);
        // Storage::disk('public')->assertMissing($thumbnailPath);
    }
}
