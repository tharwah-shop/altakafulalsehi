<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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
            'format' => 'jpg'
        ];
        
        $options = array_merge($defaultOptions, $options);
        
        // إنشاء اسم فريد للملف
        $filename = time() . '_' . uniqid() . '.' . $options['format'];
        $path = $directory . '/' . $filename;
        
        // قراءة الصورة وتحسينها
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file);

        // تغيير حجم الصورة إذا كانت أكبر من المطلوب
        if ($image->width() > $options['max_width'] || $image->height() > $options['max_height']) {
            $image->scale($options['max_width'], $options['max_height']);
        }

        // ضغط الصورة وحفظها
        $imageData = $image->encode($options['format'], $options['quality']);
        Storage::disk('public')->put($path, $imageData);
        
        return $path;
    }
    
    /**
     * حذف الصورة
     */
    public static function delete(?string $path): bool
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        
        return false;
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
    public static function validateImage(UploadedFile $file): array
    {
        $errors = [];
        
        // التحقق من نوع الملف
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/bmp'];
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            $errors[] = 'نوع الملف غير مدعوم. الأنواع المدعومة: JPEG, PNG, GIF, WebP, BMP';
        }
        
        // التحقق من حجم الملف (5MB كحد أقصى)
        if ($file->getSize() > 5 * 1024 * 1024) {
            $errors[] = 'حجم الملف كبير جداً. الحد الأقصى 5 ميجابايت';
        }
        
        // التحقق من أبعاد الصورة
        try {
            $imageInfo = getimagesize($file->getPathname());
            if ($imageInfo === false) {
                $errors[] = 'الملف ليس صورة صحيحة';
            } else {
                [$width, $height] = $imageInfo;
                if ($width < 100 || $height < 100) {
                    $errors[] = 'أبعاد الصورة صغيرة جداً. الحد الأدنى 100x100 بيكسل';
                }
                if ($width > 4000 || $height > 4000) {
                    $errors[] = 'أبعاد الصورة كبيرة جداً. الحد الأقصى 4000x4000 بيكسل';
                }
            }
        } catch (\Exception $e) {
            $errors[] = 'خطأ في قراءة الصورة';
        }
        
        return $errors;
    }
    
    /**
     * إنشاء صورة مصغرة
     */
    public static function createThumbnail(string $imagePath, int $width = 200, int $height = 200): ?string
    {
        if (!Storage::disk('public')->exists($imagePath)) {
            return null;
        }

        $pathInfo = pathinfo($imagePath);
        $thumbnailPath = $pathInfo['dirname'] . '/thumb_' . $pathInfo['basename'];

        try {
            $manager = new ImageManager(new Driver());
            $image = $manager->read(Storage::disk('public')->path($imagePath));
            $image->cover($width, $height);

            $thumbnailData = $image->encode('jpg', 80);
            Storage::disk('public')->put($thumbnailPath, $thumbnailData);

            return $thumbnailPath;
        } catch (\Exception $e) {
            return null;
        }
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
            $imageData = $image->encode('jpg', 70);
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
