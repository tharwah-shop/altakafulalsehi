<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\WebpEncoder;

class ImageHelper
{
    /**
     * رفع وتحسين الصورة
     */
    public static function uploadAndOptimize(UploadedFile $file, string $directory, array $options = []): string
    {
        // الإعدادات الافتراضية
        $defaultOptions = [
            'max_width' => 800,
            'max_height' => 600,
            'quality' => 85,
            'format' => 'jpg',
            'create_thumbnail' => true,
            'thumbnail_width' => 300,
            'thumbnail_height' => 300
        ];

        $options = array_merge($defaultOptions, $options);

        // إنشاء اسم فريد للملف
        $filename = time() . '_' . uniqid() . '.' . $options['format'];
        $path = $directory . '/' . $filename;

        // التأكد من وجود المجلد
        $fullDirectory = storage_path('app/public/' . $directory);
        if (!file_exists($fullDirectory)) {
            mkdir($fullDirectory, 0755, true);
        }

        // قراءة الصورة وتحسينها
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file);

        // تغيير حجم الصورة إذا كانت أكبر من المطلوب
        if ($image->width() > $options['max_width'] || $image->height() > $options['max_height']) {
            $image->scale($options['max_width'], $options['max_height']);
        }

        // ضغط الصورة وحفظها
        $encoder = match($options['format']) {
            'jpg', 'jpeg' => new JpegEncoder($options['quality']),
            'png' => new PngEncoder(),
            'webp' => new WebpEncoder($options['quality']),
            default => new JpegEncoder($options['quality'])
        };

        $imageData = $image->encode($encoder);
        Storage::disk('public')->put($path, $imageData);

        // إنشاء صورة مصغرة إذا كان مطلوباً
        if ($options['create_thumbnail']) {
            self::createThumbnail($path, $options['thumbnail_width'], $options['thumbnail_height']);
        }

        return $path;
    }
    
    /**
     * حذف الصورة مع الصورة المصغرة
     */
    public static function delete(?string $path): bool
    {
        if (!$path) {
            return false;
        }

        $deleted = false;

        // حذف الصورة الأصلية
        if (Storage::disk('public')->exists($path)) {
            $deleted = Storage::disk('public')->delete($path);
        }

        // حذف الصورة المصغرة إذا كانت موجودة
        $thumbnailPath = self::getThumbnailPath($path);
        if ($thumbnailPath && Storage::disk('public')->exists($thumbnailPath)) {
            Storage::disk('public')->delete($thumbnailPath);
        }

        return $deleted;
    }
    
    /**
     * الحصول على رابط الصورة
     */
    public static function getUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }
        
        if (Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }
        
        return null;
    }
    
    /**
     * التحقق من صحة الصورة
     */
    public static function validateImage(UploadedFile $file, array $options = []): array
    {
        $errors = [];

        // الإعدادات الافتراضية
        $defaultOptions = [
            'max_size' => 5 * 1024 * 1024, // 5MB
            'min_width' => 50,
            'min_height' => 50,
            'max_width' => 4000,
            'max_height' => 4000,
            'allowed_mimes' => ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/bmp']
        ];

        $options = array_merge($defaultOptions, $options);

        // التحقق من نوع الملف
        if (!in_array($file->getMimeType(), $options['allowed_mimes'])) {
            $errors[] = 'نوع الملف غير مدعوم. الأنواع المدعومة: JPEG, PNG, GIF, WebP, BMP';
        }

        // التحقق من حجم الملف
        if ($file->getSize() > $options['max_size']) {
            $maxSizeMB = round($options['max_size'] / (1024 * 1024), 1);
            $errors[] = "حجم الملف كبير جداً. الحد الأقصى {$maxSizeMB} ميجابايت";
        }

        // التحقق من أبعاد الصورة
        try {
            $imageInfo = getimagesize($file->getPathname());
            if ($imageInfo === false) {
                $errors[] = 'الملف ليس صورة صحيحة';
            } else {
                [$width, $height] = $imageInfo;

                // التحقق من الحد الأدنى للأبعاد
                if ($width < $options['min_width'] || $height < $options['min_height']) {
                    $errors[] = "أبعاد الصورة صغيرة جداً. الحد الأدنى {$options['min_width']}×{$options['min_height']} بيكسل";
                }

                // التحقق من الحد الأقصى للأبعاد
                if ($width > $options['max_width'] || $height > $options['max_height']) {
                    $errors[] = "أبعاد الصورة كبيرة جداً. الحد الأقصى {$options['max_width']}×{$options['max_height']} بيكسل";
                }

                // التحقق من نسبة العرض إلى الارتفاع إذا كانت محددة
                if (isset($options['aspect_ratio'])) {
                    $ratio = $width / $height;
                    $expectedRatio = $options['aspect_ratio'];
                    $tolerance = $options['aspect_ratio_tolerance'] ?? 0.1;

                    if (abs($ratio - $expectedRatio) > $tolerance) {
                        $errors[] = "نسبة العرض إلى الارتفاع غير مناسبة. النسبة المطلوبة: {$expectedRatio}";
                    }
                }
            }
        } catch (\Exception $e) {
            $errors[] = 'فشل في قراءة معلومات الصورة: ' . $e->getMessage();
        }

        return $errors;
    }
    
    /**
     * إنشاء صورة مصغرة
     */
    public static function createThumbnail(string $imagePath, int $width = 300, int $height = 300): ?string
    {
        if (!Storage::disk('public')->exists($imagePath)) {
            return null;
        }

        $pathInfo = pathinfo($imagePath);
        $thumbnailPath = $pathInfo['dirname'] . '/thumb_' . $pathInfo['basename'];

        try {
            $manager = new ImageManager(new Driver());
            $image = $manager->read(Storage::disk('public')->path($imagePath));

            // استخدام cover للحصول على صورة مربعة مقصوصة
            $image->cover($width, $height);

            $thumbnailData = $image->encode(new JpegEncoder(80));
            Storage::disk('public')->put($thumbnailPath, $thumbnailData);

            return $thumbnailPath;
        } catch (\Exception $e) {
            \Log::error('فشل في إنشاء الصورة المصغرة: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * الحصول على مسار الصورة المصغرة
     */
    public static function getThumbnailPath(string $imagePath): ?string
    {
        if (!$imagePath) {
            return null;
        }

        $pathInfo = pathinfo($imagePath);
        $thumbnailPath = $pathInfo['dirname'] . '/thumb_' . $pathInfo['basename'];

        if (Storage::disk('public')->exists($thumbnailPath)) {
            return $thumbnailPath;
        }

        return null;
    }

    /**
     * الحصول على رابط الصورة المصغرة
     */
    public static function getThumbnailUrl(string $imagePath): ?string
    {
        $thumbnailPath = self::getThumbnailPath($imagePath);

        if ($thumbnailPath) {
            return asset("storage/{$thumbnailPath}");
        }

        return null;
    }

    /**
     * إنشاء معاينة للصورة بتنسيق base64
     */
    public static function createPreview(UploadedFile $file, int $maxWidth = 300, int $maxHeight = 300): ?string
    {
        try {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);

            // تغيير حجم الصورة للمعاينة
            if ($image->width() > $maxWidth || $image->height() > $maxHeight) {
                $image->scale($maxWidth, $maxHeight);
            }

            // تحويل إلى base64
            $imageData = $image->encode(new JpegEncoder(70));
            return 'data:image/jpeg;base64,' . base64_encode($imageData);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * التحقق من وجود الصورة
     */
    public static function exists(?string $path): bool
    {
        if (!$path) {
            return false;
        }

        return Storage::disk('public')->exists($path);
    }

    /**
     * الحصول على معلومات الصورة
     */
    public static function getImageInfo(?string $path): ?array
    {
        if (!$path || !Storage::disk('public')->exists($path)) {
            return null;
        }

        try {
            $fullPath = Storage::disk('public')->path($path);
            $imageInfo = getimagesize($fullPath);

            if ($imageInfo === false) {
                return null;
            }

            return [
                'width' => $imageInfo[0],
                'height' => $imageInfo[1],
                'type' => $imageInfo[2],
                'mime' => $imageInfo['mime'],
                'size' => Storage::disk('public')->size($path),
                'url' => self::getUrl($path)
            ];
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * تحسين جودة الصورة تلقائياً حسب الحجم
     */
    public static function getOptimalQuality(int $fileSize): int
    {
        // تحسين الجودة حسب حجم الملف
        if ($fileSize > 2 * 1024 * 1024) { // أكبر من 2MB
            return 70;
        } elseif ($fileSize > 1 * 1024 * 1024) { // أكبر من 1MB
            return 80;
        } else {
            return 85;
        }
    }

    /**
     * رفع متعدد للصور
     */
    public static function uploadMultiple(array $files, string $directory, array $options = []): array
    {
        $uploadedPaths = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $path = self::uploadAndOptimize($file, $directory, $options);
                if ($path) {
                    $uploadedPaths[] = $path;
                }
            }
        }

        return $uploadedPaths;
    }

    /**
     * حذف متعدد للصور
     */
    public static function deleteMultiple(array $paths): int
    {
        $deletedCount = 0;

        foreach ($paths as $path) {
            if (self::delete($path)) {
                $deletedCount++;
            }
        }

        return $deletedCount;
    }
}
