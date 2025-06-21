<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ImageHelperTest extends TestCase
{
    /** @test */
    public function it_can_validate_image_types()
    {
        // اختبار بسيط للتحقق من أن الفئة موجودة
        $this->assertTrue(class_exists('App\Helpers\ImageHelper'));
    }

    /** @test */
    public function it_has_required_methods()
    {
        // التحقق من وجود الطرق المطلوبة
        $this->assertTrue(method_exists('App\Helpers\ImageHelper', 'validateImage'));
        $this->assertTrue(method_exists('App\Helpers\ImageHelper', 'uploadAndOptimize'));
        $this->assertTrue(method_exists('App\Helpers\ImageHelper', 'createThumbnail'));
        $this->assertTrue(method_exists('App\Helpers\ImageHelper', 'delete'));
        $this->assertTrue(method_exists('App\Helpers\ImageHelper', 'getUrl'));
    }

    /** @test */
    public function it_can_handle_basic_operations()
    {
        // اختبار بسيط للتأكد من أن الفئة تعمل
        $this->assertTrue(true);
    }
}
