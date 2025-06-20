<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MedicalCenter;
use App\Helpers\ImageHelper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TestImageUpload extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'test:image-upload';

    /**
     * The console command description.
     */
    protected $description = 'Test image upload functionality for medical centers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing image upload functionality...');

        // التحقق من وجود الرابط الرمزي
        if (!is_link(public_path('storage'))) {
            $this->error('Storage link not found. Running php artisan storage:link...');
            $this->call('storage:link');
        } else {
            $this->info('✓ Storage link exists');
        }

        // التحقق من وجود مجلد الصور
        if (!Storage::disk('public')->exists('medical-centers')) {
            Storage::disk('public')->makeDirectory('medical-centers');
            $this->info('✓ Created medical-centers directory');
        } else {
            $this->info('✓ Medical-centers directory exists');
        }

        // عرض الصور الموجودة
        $images = Storage::disk('public')->files('medical-centers');
        $this->info('Found ' . count($images) . ' images in medical-centers directory:');
        foreach ($images as $image) {
            $this->line('  - ' . $image);
        }

        // التحقق من المراكز الطبية التي لها صور
        $centersWithImages = MedicalCenter::whereNotNull('image')->get();
        $this->info('Found ' . $centersWithImages->count() . ' medical centers with images:');
        
        foreach ($centersWithImages as $center) {
            $this->line('  - ID: ' . $center->id . ' | Name: ' . $center->name . ' | Image: ' . $center->image);
            
            // التحقق من وجود الصورة فعلياً
            if (ImageHelper::exists($center->image)) {
                $this->info('    ✓ Image file exists');
            } else {
                $this->error('    ✗ Image file missing');
            }
            
            // التحقق من رابط الصورة
            $url = $center->image_url;
            if ($url) {
                $this->info('    URL: ' . $url);
            } else {
                $this->error('    ✗ No image URL');
            }
        }

        // إنشاء مركز طبي تجريبي جديد
        $this->info('Creating test medical center...');
        
        $testCenter = MedicalCenter::create([
            'name' => 'مركز طبي تجريبي - ' . now()->format('Y-m-d H:i:s'),
            'slug' => Str::slug('مركز طبي تجريبي ' . now()->timestamp),
            'description' => 'مركز طبي تجريبي لاختبار رفع الصور',
            'region' => 'الرياض',
            'city' => 'وسط الرياض',
            'city_id' => 5,
            'address' => 'شارع الاختبار، الرياض',
            'phone' => '0501234567',
            'email' => 'test@example.com',
            'type' => 1,
            'status' => 'active',
            'created_by' => 1,
            'image' => 'medical-centers/hospital_1.jpg', // استخدام صورة موجودة
        ]);

        $this->info('✓ Created test medical center with ID: ' . $testCenter->id);
        $this->info('✓ Image URL: ' . $testCenter->image_url);

        // اختبار ImageHelper
        $this->info('Testing ImageHelper functions...');
        
        if (ImageHelper::exists($testCenter->image)) {
            $this->info('✓ ImageHelper::exists() works');
        } else {
            $this->error('✗ ImageHelper::exists() failed');
        }

        $imageInfo = ImageHelper::getImageInfo($testCenter->image);
        if ($imageInfo) {
            $this->info('✓ ImageHelper::getImageInfo() works');
            $this->line('  Width: ' . $imageInfo['width']);
            $this->line('  Height: ' . $imageInfo['height']);
            $this->line('  Size: ' . round($imageInfo['size'] / 1024, 2) . ' KB');
        } else {
            $this->error('✗ ImageHelper::getImageInfo() failed');
        }

        $this->info('Test completed! Visit /test-images to see the results in browser.');
        
        return 0;
    }
}
