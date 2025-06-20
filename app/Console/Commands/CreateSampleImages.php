<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;

class CreateSampleImages extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'images:create-samples';

    /**
     * The console command description.
     */
    protected $description = 'Create sample images for medical centers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating sample images for medical centers...');

        // إنشاء مجلد الصور إذا لم يكن موجوداً
        if (!Storage::disk('public')->exists('medical-centers')) {
            Storage::disk('public')->makeDirectory('medical-centers');
        }

        // إنشاء صور تجريبية
        $sampleImages = [
            [
                'name' => 'hospital_1.jpg',
                'text' => 'مستشفى',
                'color' => '#2563eb', // أزرق
            ],
            [
                'name' => 'clinic_1.jpg', 
                'text' => 'عيادة',
                'color' => '#059669', // أخضر
            ],
            [
                'name' => 'lab_1.jpg',
                'text' => 'مختبر',
                'color' => '#dc2626', // أحمر
            ],
            [
                'name' => 'pharmacy_1.jpg',
                'text' => 'صيدلية',
                'color' => '#7c3aed', // بنفسجي
            ],
        ];

        $manager = new ImageManager(new Driver());

        foreach ($sampleImages as $imageData) {
            try {
                // إنشاء صورة 400x300
                $image = $manager->create(400, 300);
                
                // تعبئة الخلفية بلون متدرج
                $image->fill($imageData['color']);
                
                // إضافة نص بسيط
                $image->text($imageData['text'], 200, 150, function($font) {
                    $font->size(32);
                    $font->color('#ffffff');
                    $font->align('center');
                    $font->valign('middle');
                });

                // حفظ الصورة
                $path = 'medical-centers/' . $imageData['name'];
                $encodedImage = $image->encode(new JpegEncoder(85));
                Storage::disk('public')->put($path, $encodedImage);

                $this->info("Created: {$imageData['name']}");

            } catch (\Exception $e) {
                $this->error("Failed to create {$imageData['name']}: " . $e->getMessage());
            }
        }

        $this->info('Sample images created successfully!');
        return 0;
    }
}
