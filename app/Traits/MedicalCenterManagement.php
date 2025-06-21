<?php

namespace App\Traits;

use App\Helpers\ImageHelper;
use App\Helpers\CitiesHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait MedicalCenterManagement
{
    /**
     * قواعد التحقق المشتركة للمراكز الطبية
     */
    protected function getValidationRules($medicalCenter = null): array
    {
        $slugRule = $medicalCenter 
            ? 'nullable|string|max:255|unique:medical_centers,slug,' . $medicalCenter->id
            : 'nullable|string|max:255|unique:medical_centers,slug';

        return [
            'name' => 'required|string|max:255',
            'slug' => $slugRule,
            'description' => 'nullable|string',
            'city' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'type' => 'required|integer|min:1|max:12',
            'medical_service_types' => 'nullable|array',
            'medical_service_types.*' => 'string',
            'discounts' => 'nullable|array',
            'discounts.*.service' => 'nullable|string|max:255',
            'discounts.*.discount' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,pending,suspended',
            'contract_status' => 'nullable|in:active,pending,expired,suspended,terminated',
            'contract_start_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date|after_or_equal:contract_start_date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,bmp|max:5120',
        ];
    }

    /**
     * معالجة البيانات المشتركة
     */
    protected function processCommonData(array $validated): array
    {
        // إنشاء slug إذا لم يتم توفيره
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // التحقق من صحة المدينة واستخراج المنطقة
        if (!CitiesHelper::cityExists($validated['city'])) {
            throw new \Exception('المدينة المحددة غير صحيحة');
        }

        $cityData = CitiesHelper::getCityData($validated['city']);
        $validated['region'] = $cityData['region'];

        // معالجة الخصومات الطبية
        if (isset($validated['discounts']) && is_array($validated['discounts'])) {
            $validated['medical_discounts'] = array_filter($validated['discounts'], function($discount) {
                return !empty($discount['service']) && !empty($discount['discount']);
            });
            unset($validated['discounts']);
        }

        return $validated;
    }

    /**
     * معالجة رفع الصورة
     */
    protected function handleImageUpload(Request $request, $medicalCenter = null): ?string
    {
        if (!$request->hasFile('image')) {
            return null;
        }

        // التحقق من صحة الصورة
        $imageErrors = ImageHelper::validateImage($request->file('image'));
        if (!empty($imageErrors)) {
            throw new \Exception(implode(', ', $imageErrors));
        }

        // حذف الصورة القديمة إذا كانت موجودة
        if ($medicalCenter && $medicalCenter->image) {
            ImageHelper::delete($medicalCenter->image);
        }

        // رفع وتحسين الصورة الجديدة
        return ImageHelper::uploadAndOptimize(
            $request->file('image'),
            'medical-centers',
            [
                'max_width' => 800,
                'max_height' => 600,
                'quality' => 85
            ]
        );
    }

    /**
     * معالجة حذف الصورة الحالية
     */
    protected function handleImageRemoval(Request $request, $medicalCenter): bool
    {
        if ($request->input('remove_current_image') == '1' && $medicalCenter->image) {
            ImageHelper::delete($medicalCenter->image);
            return true;
        }
        return false;
    }

    /**
     * إعداد البيانات للإنشاء
     */
    protected function prepareDataForCreation(array $validated): array
    {
        $validated['created_by'] = auth()->id();
        return $validated;
    }

    /**
     * معالجة slug للتحديث
     */
    protected function handleSlugUpdate(array $validated, $medicalCenter): array
    {
        // تحديث slug إذا تغير الاسم أو إذا لم يكن موجوداً
        if (empty($validated['slug']) || $medicalCenter->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        return $validated;
    }

    /**
     * الحصول على إعدادات الصورة الافتراضية
     */
    protected function getImageUploadOptions(): array
    {
        return [
            'max_width' => 800,
            'max_height' => 600,
            'quality' => 85,
            'create_thumbnail' => true,
            'thumbnail_width' => 300,
            'thumbnail_height' => 300
        ];
    }

    /**
     * معالجة الأخطاء المشتركة
     */
    protected function handleValidationError(\Exception $e, $input = null)
    {
        $errorKey = 'error';
        $errorMessage = $e->getMessage();

        // تحديد نوع الخطأ
        if (str_contains($errorMessage, 'المدينة')) {
            $errorKey = 'city';
        } elseif (str_contains($errorMessage, 'صورة') || str_contains($errorMessage, 'image')) {
            $errorKey = 'image';
        }

        if ($input) {
            return back()->withErrors([$errorKey => $errorMessage])->withInput($input);
        }

        return back()->withErrors([$errorKey => $errorMessage]);
    }

    /**
     * تنظيف البيانات قبل الحفظ
     */
    protected function cleanDataForSave(array $validated): array
    {
        // إزالة الحقول التي لا تحتاج للحفظ
        unset($validated['remove_current_image']);
        
        // تنظيف البيانات الفارغة
        return array_filter($validated, function($value) {
            return $value !== null && $value !== '';
        });
    }

    /**
     * إعداد رسائل النجاح
     */
    protected function getSuccessMessages(): array
    {
        return [
            'created' => 'تم إضافة المركز الطبي بنجاح',
            'updated' => 'تم تحديث المركز الطبي بنجاح',
            'deleted' => 'تم حذف المركز الطبي بنجاح'
        ];
    }

    /**
     * معالجة شاملة لإنشاء مركز طبي
     */
    protected function processMedicalCenterCreation(Request $request)
    {
        try {
            // التحقق من البيانات
            $validated = $request->validate($this->getValidationRules());
            
            // معالجة البيانات المشتركة
            $validated = $this->processCommonData($validated);
            
            // رفع الصورة
            $imagePath = $this->handleImageUpload($request);
            if ($imagePath) {
                $validated['image'] = $imagePath;
            }
            
            // إعداد البيانات للإنشاء
            $validated = $this->prepareDataForCreation($validated);
            
            // تنظيف البيانات
            $validated = $this->cleanDataForSave($validated);
            
            return $validated;
            
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * معالجة شاملة لتحديث مركز طبي
     */
    protected function processMedicalCenterUpdate(Request $request, $medicalCenter)
    {
        try {
            // إضافة قاعدة التحقق من حذف الصورة
            $rules = $this->getValidationRules($medicalCenter);
            $rules['remove_current_image'] = 'nullable|boolean';
            
            // التحقق من البيانات
            $validated = $request->validate($rules);
            
            // معالجة البيانات المشتركة
            $validated = $this->processCommonData($validated);
            
            // معالجة slug للتحديث
            $validated = $this->handleSlugUpdate($validated, $medicalCenter);
            
            // معالجة حذف الصورة الحالية
            if ($this->handleImageRemoval($request, $medicalCenter)) {
                $validated['image'] = null;
            }
            
            // رفع الصورة الجديدة
            $imagePath = $this->handleImageUpload($request, $medicalCenter);
            if ($imagePath) {
                $validated['image'] = $imagePath;
            }
            
            // تنظيف البيانات
            $validated = $this->cleanDataForSave($validated);
            
            return $validated;
            
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
