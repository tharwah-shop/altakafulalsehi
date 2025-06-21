<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MedicalCenterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole(['admin', 'manager']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $medicalCenterId = $this->route('medical_center')?->id;
        
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('medical_centers', 'name')->ignore($medicalCenterId)
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9-]+$/',
                Rule::unique('medical_centers', 'slug')->ignore($medicalCenterId)
            ],
            'description' => 'nullable|string|max:2000',
            'region' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'address' => 'required|string|min:10|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'phone' => [
                'nullable',
                'string',
                'regex:/^(\+966|0)?[5][0-9]{8}$/',
                'max:20'
            ],
            'email' => [
                'nullable',
                'email:rfc,dns',
                'max:255',
                Rule::unique('medical_centers', 'email')->ignore($medicalCenterId)
            ],
            'website' => 'nullable|url|max:255',
            'type' => 'required|integer|between:1,12',
            'medical_service_types' => 'nullable|array|max:20',
            'medical_service_types.*' => 'string|max:100',
            'medical_discounts' => 'nullable|array|max:50',
            'medical_discounts.*' => 'string|max:200',
            'status' => 'required|in:active,inactive,pending,suspended',
            'contract_status' => 'nullable|in:active,pending,expired,suspended,terminated',
            'contract_start_date' => 'nullable|date|before_or_equal:contract_end_date',
            'contract_end_date' => 'nullable|date|after_or_equal:contract_start_date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'location' => 'nullable|url|max:500',
            'rating' => 'nullable|numeric|between:0,5',
            'reviews_count' => 'nullable|integer|min:0',
            'views_count' => 'nullable|integer|min:0'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'اسم المركز الطبي مطلوب',
            'name.min' => 'اسم المركز الطبي يجب أن يكون على الأقل 3 أحرف',
            'name.max' => 'اسم المركز الطبي يجب ألا يزيد عن 255 حرف',
            'name.unique' => 'اسم المركز الطبي موجود مسبقاً',
            
            'slug.regex' => 'الرابط المختصر يجب أن يحتوي على أحرف إنجليزية صغيرة وأرقام وشرطات فقط',
            'slug.unique' => 'الرابط المختصر موجود مسبقاً',
            
            'description.max' => 'الوصف يجب ألا يزيد عن 2000 حرف',
            
            'region.required' => 'المنطقة مطلوبة',
            'region.max' => 'المنطقة يجب ألا تزيد عن 100 حرف',
            
            'city.required' => 'المدينة مطلوبة',
            'city.max' => 'المدينة يجب ألا تزيد عن 100 حرف',
            
            'address.required' => 'العنوان مطلوب',
            'address.min' => 'العنوان يجب أن يكون على الأقل 10 أحرف',
            'address.max' => 'العنوان يجب ألا يزيد عن 500 حرف',
            
            'latitude.between' => 'خط العرض يجب أن يكون بين -90 و 90',
            'longitude.between' => 'خط الطول يجب أن يكون بين -180 و 180',
            
            'phone.regex' => 'رقم الهاتف يجب أن يكون رقم سعودي صحيح (مثال: 0501234567)',
            
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني موجود مسبقاً',
            
            'website.url' => 'رابط الموقع الإلكتروني غير صحيح',
            
            'type.required' => 'نوع المركز الطبي مطلوب',
            'type.between' => 'نوع المركز الطبي يجب أن يكون بين 1 و 12',
            
            'medical_service_types.array' => 'أنواع الخدمات الطبية يجب أن تكون مصفوفة',
            'medical_service_types.max' => 'لا يمكن إضافة أكثر من 20 نوع خدمة طبية',
            
            'medical_discounts.array' => 'الخصومات الطبية يجب أن تكون مصفوفة',
            'medical_discounts.max' => 'لا يمكن إضافة أكثر من 50 خصم طبي',
            
            'status.required' => 'حالة المركز الطبي مطلوبة',
            'status.in' => 'حالة المركز الطبي غير صحيحة',
            
            'contract_status.in' => 'حالة العقد غير صحيحة',
            'contract_start_date.before_or_equal' => 'تاريخ بداية العقد يجب أن يكون قبل أو يساوي تاريخ النهاية',
            'contract_end_date.after_or_equal' => 'تاريخ نهاية العقد يجب أن يكون بعد أو يساوي تاريخ البداية',
            
            'image.image' => 'الملف يجب أن يكون صورة',
            'image.mimes' => 'الصورة يجب أن تكون من نوع: jpeg, png, jpg, webp',
            'image.max' => 'حجم الصورة يجب ألا يزيد عن 2 ميجابايت',
            
            'location.url' => 'رابط الموقع غير صحيح',
            
            'rating.between' => 'التقييم يجب أن يكون بين 0 و 5',
            'reviews_count.min' => 'عدد المراجعات يجب أن يكون 0 أو أكثر',
            'views_count.min' => 'عدد المشاهدات يجب أن يكون 0 أو أكثر'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'اسم المركز الطبي',
            'slug' => 'الرابط المختصر',
            'description' => 'الوصف',
            'region' => 'المنطقة',
            'city' => 'المدينة',
            'address' => 'العنوان',
            'latitude' => 'خط العرض',
            'longitude' => 'خط الطول',
            'phone' => 'رقم الهاتف',
            'email' => 'البريد الإلكتروني',
            'website' => 'الموقع الإلكتروني',
            'type' => 'نوع المركز الطبي',
            'medical_service_types' => 'أنواع الخدمات الطبية',
            'medical_discounts' => 'الخصومات الطبية',
            'status' => 'الحالة',
            'contract_status' => 'حالة العقد',
            'contract_start_date' => 'تاريخ بداية العقد',
            'contract_end_date' => 'تاريخ نهاية العقد',
            'image' => 'الصورة',
            'location' => 'رابط الموقع',
            'rating' => 'التقييم',
            'reviews_count' => 'عدد المراجعات',
            'views_count' => 'عدد المشاهدات'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // تنظيف رقم الهاتف
        if ($this->phone) {
            $phone = preg_replace('/\D/', '', $this->phone);
            if (strlen($phone) === 9 && substr($phone, 0, 1) === '5') {
                $phone = '0' . $phone;
            }
            $this->merge(['phone' => $phone]);
        }

        // إنشاء slug تلقائياً إذا لم يتم توفيره
        if (!$this->slug && $this->name) {
            $this->merge(['slug' => \Str::slug($this->name)]);
        }

        // تنظيف البيانات النصية
        if ($this->description) {
            $this->merge(['description' => trim(strip_tags($this->description))]);
        }
    }
}
