<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\MedicalCenter;
use App\Helpers\CitiesHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MedicalCenterTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // إنشاء مستخدم للاختبار
        $this->user = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);
    }

    /** @test */
    public function admin_can_view_medical_centers_create_page()
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.medical-centers.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.medical-centers.create');
        $response->assertSee('إضافة مركز طبي جديد');
    }

    /** @test */
    public function admin_can_create_medical_center()
    {
        Storage::fake('public');

        $data = [
            'name' => 'مستشفى الاختبار',
            'slug' => 'test-hospital',
            'description' => 'وصف المستشفى',
            'city' => 'الرياض',
            'address' => 'شارع الملك فهد',
            'phone' => '0112345678',
            'email' => 'test@hospital.com',
            'website' => 'https://test-hospital.com',
            'type' => 1,
            'status' => 'active',
            'medical_service_types' => ['dentistry', 'emergency'],
            'discounts' => [
                ['service' => 'كشف عام', 'discount' => '10%'],
                ['service' => 'تحاليل', 'discount' => '15%']
            ]
        ];

        $response = $this->actingAs($this->user)
            ->post(route('admin.medical-centers.store'), $data);

        $response->assertRedirect(route('admin.medical-centers.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('medical_centers', [
            'name' => 'مستشفى الاختبار',
            'slug' => 'test-hospital',
            'city' => 'الرياض',
            'type' => 1,
            'status' => 'active'
        ]);
    }

    /** @test */
    public function admin_can_create_medical_center_with_image()
    {
        Storage::fake('public');

        $image = UploadedFile::fake()->image('hospital.jpg', 800, 600);

        $data = [
            'name' => 'مستشفى مع صورة',
            'city' => 'جدة',
            'type' => 2,
            'status' => 'active',
            'image' => $image
        ];

        $response = $this->actingAs($this->user)
            ->post(route('admin.medical-centers.store'), $data);

        $response->assertRedirect(route('admin.medical-centers.index'));

        $medicalCenter = MedicalCenter::where('name', 'مستشفى مع صورة')->first();
        $this->assertNotNull($medicalCenter);
        $this->assertNotNull($medicalCenter->image);
        
        Storage::disk('public')->assertExists($medicalCenter->image);
    }

    /** @test */
    public function admin_can_view_medical_center_edit_page()
    {
        $medicalCenter = MedicalCenter::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('admin.medical-centers.edit', $medicalCenter));

        $response->assertStatus(200);
        $response->assertViewIs('admin.medical-centers.edit');
        $response->assertSee('تعديل مركز طبي');
        $response->assertSee($medicalCenter->name);
    }

    /** @test */
    public function admin_can_update_medical_center()
    {
        $medicalCenter = MedicalCenter::factory()->create([
            'name' => 'اسم قديم',
            'city' => 'الرياض'
        ]);

        $data = [
            'name' => 'اسم جديد',
            'slug' => 'new-name',
            'city' => 'جدة',
            'type' => 1,
            'status' => 'active'
        ];

        $response = $this->actingAs($this->user)
            ->put(route('admin.medical-centers.update', $medicalCenter), $data);

        $response->assertRedirect(route('admin.medical-centers.index'));

        $medicalCenter->refresh();
        $this->assertEquals('اسم جديد', $medicalCenter->name);
        $this->assertEquals('جدة', $medicalCenter->city);
    }

    /** @test */
    public function slug_is_generated_automatically_from_name()
    {
        $data = [
            'name' => 'مستشفى الملك فهد التخصصي',
            'city' => 'الرياض',
            'type' => 1,
            'status' => 'active'
        ];

        $response = $this->actingAs($this->user)
            ->post(route('admin.medical-centers.store'), $data);

        $medicalCenter = MedicalCenter::where('name', 'مستشفى الملك فهد التخصصي')->first();
        $this->assertNotNull($medicalCenter);
        $this->assertNotEmpty($medicalCenter->slug);
    }

    /** @test */
    public function validation_fails_for_required_fields()
    {
        $response = $this->actingAs($this->user)
            ->post(route('admin.medical-centers.store'), []);

        $response->assertSessionHasErrors(['name', 'city', 'type', 'status']);
    }

    /** @test */
    public function it_can_update_medical_center()
    {
        $medicalCenter = MedicalCenter::factory()->create([
            'name' => 'مستشفى الأصل',
            'city' => 'الرياض',
            'type' => 1
        ]);

        $updateData = [
            'name' => 'مستشفى محدث',
            'city' => 'جدة',
            'type' => 2,
            'status' => 'active'
        ];

        $response = $this->actingAs($this->user)
            ->put(route('admin.medical-centers.update', $medicalCenter), $updateData);

        $response->assertRedirect();

        $medicalCenter->refresh();
        $this->assertEquals('مستشفى محدث', $medicalCenter->name);
        $this->assertEquals('جدة', $medicalCenter->city);
        $this->assertEquals(2, $medicalCenter->type);
    }

    /** @test */
    public function it_can_delete_medical_center()
    {
        $medicalCenter = MedicalCenter::factory()->create();

        $response = $this->actingAs($this->user)
            ->delete(route('admin.medical-centers.destroy', $medicalCenter));

        $response->assertRedirect();
        $this->assertDatabaseMissing('medical_centers', ['id' => $medicalCenter->id]);
    }

    /** @test */
    public function it_validates_phone_number_format()
    {
        $invalidPhoneData = [
            'name' => 'مستشفى تجريبي',
            'city' => 'الرياض',
            'type' => 1,
            'status' => 'active',
            'phone' => '123456789' // رقم غير صحيح
        ];

        $response = $this->actingAs($this->user)
            ->post(route('admin.medical-centers.store'), $invalidPhoneData);

        $response->assertSessionHasErrors('phone');
    }

    /** @test */
    public function it_validates_email_format()
    {
        $invalidEmailData = [
            'name' => 'مستشفى تجريبي',
            'city' => 'الرياض',
            'type' => 1,
            'status' => 'active',
            'email' => 'invalid-email'
        ];

        $response = $this->actingAs($this->user)
            ->post(route('admin.medical-centers.store'), $invalidEmailData);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function it_validates_medical_center_type_range()
    {
        $invalidTypeData = [
            'name' => 'مستشفى تجريبي',
            'city' => 'الرياض',
            'type' => 15, // نوع غير صحيح
            'status' => 'active'
        ];

        $response = $this->actingAs($this->user)
            ->post(route('admin.medical-centers.store'), $invalidTypeData);

        $response->assertSessionHasErrors('type');
    }

    /** @test */
    public function it_can_filter_medical_centers_by_city()
    {
        $riyadhCenter = MedicalCenter::factory()->create(['city' => 'الرياض']);
        $jeddahCenter = MedicalCenter::factory()->create(['city' => 'جدة']);

        $response = $this->actingAs($this->user)
            ->get(route('admin.medical-centers.index', ['city' => 'الرياض']));

        $response->assertStatus(200);
        $response->assertSee($riyadhCenter->name);
        $response->assertDontSee($jeddahCenter->name);
    }

    /** @test */
    public function it_can_filter_medical_centers_by_type()
    {
        $hospital = MedicalCenter::factory()->create(['type' => 1]);
        $clinic = MedicalCenter::factory()->create(['type' => 2]);

        $response = $this->actingAs($this->user)
            ->get(route('admin.medical-centers.index', ['type' => 1]));

        $response->assertStatus(200);
        $response->assertSee($hospital->name);
        $response->assertDontSee($clinic->name);
    }

    /** @test */
    public function it_can_search_medical_centers_by_name()
    {
        $searchableCenter = MedicalCenter::factory()->create(['name' => 'مستشفى الملك فهد']);
        $otherCenter = MedicalCenter::factory()->create(['name' => 'مستشفى الرياض']);

        $response = $this->actingAs($this->user)
            ->get(route('admin.medical-centers.index', ['search' => 'الملك فهد']));

        $response->assertStatus(200);
        $response->assertSee($searchableCenter->name);
        $response->assertDontSee($otherCenter->name);
    }

    /** @test */
    public function cities_helper_works_correctly()
    {
        $cities = CitiesHelper::getAllCities();
        $this->assertIsArray($cities);
        $this->assertNotEmpty($cities);

        $cityExists = CitiesHelper::cityExists('الرياض');
        $this->assertTrue($cityExists);

        $cityExists = CitiesHelper::cityExists('مدينة غير موجودة');
        $this->assertFalse($cityExists);
    }

    /** @test */
    public function medical_discounts_are_processed_correctly()
    {
        $data = [
            'name' => 'مستشفى الخصومات',
            'city' => 'الرياض',
            'type' => 1,
            'status' => 'active',
            'discounts' => [
                ['service' => 'كشف عام', 'discount' => '10%'],
                ['service' => '', 'discount' => ''], // يجب تجاهل هذا
                ['service' => 'تحاليل', 'discount' => '15%']
            ]
        ];

        $response = $this->actingAs($this->user)
            ->post(route('admin.medical-centers.store'), $data);

        $medicalCenter = MedicalCenter::where('name', 'مستشفى الخصومات')->first();
        $this->assertNotNull($medicalCenter);
        $this->assertIsArray($medicalCenter->medical_discounts);
        $this->assertCount(2, $medicalCenter->medical_discounts); // يجب أن يكون 2 فقط
    }

    /** @test */
    public function admin_can_delete_medical_center()
    {
        $medicalCenter = MedicalCenter::factory()->create();

        $response = $this->actingAs($this->user)
            ->delete(route('admin.medical-centers.destroy', $medicalCenter));

        $response->assertRedirect(route('admin.medical-centers.index'));
        $this->assertDatabaseMissing('medical_centers', ['id' => $medicalCenter->id]);
    }

    /** @test */
    public function admin_can_toggle_medical_center_status()
    {
        $medicalCenter = MedicalCenter::factory()->create(['status' => 'active']);

        $response = $this->actingAs($this->user)
            ->patch(route('admin.medical-centers.toggle-status', $medicalCenter));

        $response->assertRedirect();
        $medicalCenter->refresh();
        $this->assertEquals('inactive', $medicalCenter->status);
    }
}
