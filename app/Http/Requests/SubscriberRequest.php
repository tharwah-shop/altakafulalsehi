<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubscriberRequest extends FormRequest
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
        $subscriberId = $this->route('subscriber')?->id;
        
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[\p{Arabic}\p{L}\s]+$/u'
            ],
            'phone' => [
                'required',
                'string',
                'regex:/^(\+966|0)?[5][0-9]{8}$/',
                Rule::unique('subscribers', 'phone')->ignore($subscriberId)
            ],
            'email' => [
                'nullable',
                'email:rfc,dns',
                'max:255',
                Rule::unique('subscribers', 'email')->ignore($subscriberId)
            ],
            'city' => 'required|string|max:100',
            'nationality' => 'required|string|max:100',
            'id_number' => [
                'required',
                'string',
                'regex:/^[12][0-9]{9}$/',
                Rule::unique('subscribers', 'id_number')->ignore($subscriberId)
            ],
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'package_id' => 'nullable|exists:packages,id',
            'card_price' => 'nullable|numeric|min:0|max:999999.99',
            'total_amount' => 'nullable|numeric|min:0|max:999999.99',
            'dependents_count' => 'nullable|integer|min:0|max:20',
            'status' => 'required|in:فعال,منتهي,ملغي,معلق,بانتظار الدفع',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0|max:999999.99',
            'notes' => 'nullable|string|max:1000',
            
            // التابعين
            'dependents' => 'nullable|array|max:20',
            'dependents.*.name' => [
                'required_with:dependents',
                'string',
                'min:3',
                'max:255',
                'regex:/^[\p{Arabic}\p{L}\s]+$/u'
            ],
            'dependents.*.nationality' => 'required_with:dependents|string|max:100',
            'dependents.*.id_number' => [
                'nullable',
                'string',
                'regex:/^[12][0-9]{9}$/',
                'distinct'
            ],
            'dependents.*.dependent_price' => 'nullable|numeric|min:0|max:999999.99',
            'dependents.*.notes' => 'nullable|string|max:500'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'اسم المشترك مطلوب',
            'name.min' => 'اسم المشترك يجب أن يكون على الأقل 3 أحرف',
            'name.max' => 'اسم المشترك يجب ألا يزيد عن 255 حرف',
            'name.regex' => 'اسم المشترك يجب أن يحتوي على أحرف عربية أو إنجليزية فقط',
            
            'phone.required' => 'رقم الجوال مطلوب',
            'phone.regex' => 'رقم الجوال يجب أن يكون رقم سعودي صحيح (مثال: 0501234567)',
            'phone.unique' => 'رقم الجوال موجود مسبقاً',
            
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني موجود مسبقاً',
            
            'city.required' => 'المدينة مطلوبة',
            'city.max' => 'المدينة يجب ألا تزيد عن 100 حرف',
            
            'nationality.required' => 'الجنسية مطلوبة',
            'nationality.max' => 'الجنسية يجب ألا تزيد عن 100 حرف',
            
            'id_number.required' => 'رقم الهوية/الإقامة مطلوب',
            'id_number.regex' => 'رقم الهوية/الإقامة يجب أن يكون 10 أرقام ويبدأ بـ 1 أو 2',
            'id_number.unique' => 'رقم الهوية/الإقامة موجود مسبقاً',
            
            'start_date.required' => 'تاريخ بداية الاشتراك مطلوب',
            'start_date.before_or_equal' => 'تاريخ بداية الاشتراك يجب أن يكون قبل أو يساوي تاريخ النهاية',
            
            'end_date.required' => 'تاريخ نهاية الاشتراك مطلوب',
            'end_date.after_or_equal' => 'تاريخ نهاية الاشتراك يجب أن يكون بعد أو يساوي تاريخ البداية',
            
            'package_id.exists' => 'الباقة المحددة غير موجودة',
            
            'card_price.numeric' => 'سعر البطاقة يجب أن يكون رقم',
            'card_price.min' => 'سعر البطاقة يجب أن يكون 0 أو أكثر',
            'card_price.max' => 'سعر البطاقة يجب ألا يزيد عن 999,999.99',
            
            'total_amount.numeric' => 'المبلغ الإجمالي يجب أن يكون رقم',
            'total_amount.min' => 'المبلغ الإجمالي يجب أن يكون 0 أو أكثر',
            'total_amount.max' => 'المبلغ الإجمالي يجب ألا يزيد عن 999,999.99',
            
            'dependents_count.integer' => 'عدد التابعين يجب أن يكون رقم صحيح',
            'dependents_count.min' => 'عدد التابعين يجب أن يكون 0 أو أكثر',
            'dependents_count.max' => 'عدد التابعين يجب ألا يزيد عن 20',
            
            'status.required' => 'حالة الاشتراك مطلوبة',
            'status.in' => 'حالة الاشتراك غير صحيحة',
            
            'discount_percentage.numeric' => 'نسبة الخصم يجب أن تكون رقم',
            'discount_percentage.min' => 'نسبة الخصم يجب أن تكون 0 أو أكثر',
            'discount_percentage.max' => 'نسبة الخصم يجب ألا تزيد عن 100%',
            
            'discount_amount.numeric' => 'مبلغ الخصم يجب أن يكون رقم',
            'discount_amount.min' => 'مبلغ الخصم يجب أن يكون 0 أو أكثر',
            'discount_amount.max' => 'مبلغ الخصم يجب ألا يزيد عن 999,999.99',
            
            'notes.max' => 'الملاحظات يجب ألا تزيد عن 1000 حرف',
            
            // رسائل التابعين
            'dependents.array' => 'بيانات التابعين يجب أن تكون مصفوفة',
            'dependents.max' => 'لا يمكن إضافة أكثر من 20 تابع',
            
            'dependents.*.name.required_with' => 'اسم التابع مطلوب',
            'dependents.*.name.min' => 'اسم التابع يجب أن يكون على الأقل 3 أحرف',
            'dependents.*.name.max' => 'اسم التابع يجب ألا يزيد عن 255 حرف',
            'dependents.*.name.regex' => 'اسم التابع يجب أن يحتوي على أحرف عربية أو إنجليزية فقط',
            
            'dependents.*.nationality.required_with' => 'جنسية التابع مطلوبة',
            'dependents.*.nationality.max' => 'جنسية التابع يجب ألا تزيد عن 100 حرف',
            
            'dependents.*.id_number.regex' => 'رقم هوية التابع يجب أن يكون 10 أرقام ويبدأ بـ 1 أو 2',
            'dependents.*.id_number.distinct' => 'رقم هوية التابع مكرر',
            
            'dependents.*.dependent_price.numeric' => 'سعر التابع يجب أن يكون رقم',
            'dependents.*.dependent_price.min' => 'سعر التابع يجب أن يكون 0 أو أكثر',
            'dependents.*.dependent_price.max' => 'سعر التابع يجب ألا يزيد عن 999,999.99',
            
            'dependents.*.notes.max' => 'ملاحظات التابع يجب ألا تزيد عن 500 حرف'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'اسم المشترك',
            'phone' => 'رقم الجوال',
            'email' => 'البريد الإلكتروني',
            'city' => 'المدينة',
            'nationality' => 'الجنسية',
            'id_number' => 'رقم الهوية/الإقامة',
            'start_date' => 'تاريخ بداية الاشتراك',
            'end_date' => 'تاريخ نهاية الاشتراك',
            'package_id' => 'الباقة',
            'card_price' => 'سعر البطاقة',
            'total_amount' => 'المبلغ الإجمالي',
            'dependents_count' => 'عدد التابعين',
            'status' => 'حالة الاشتراك',
            'discount_percentage' => 'نسبة الخصم',
            'discount_amount' => 'مبلغ الخصم',
            'notes' => 'الملاحظات'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // تنظيف رقم الجوال
        if ($this->phone) {
            $phone = preg_replace('/\D/', '', $this->phone);
            if (strlen($phone) === 9 && substr($phone, 0, 1) === '5') {
                $phone = '0' . $phone;
            }
            $this->merge(['phone' => $phone]);
        }

        // تنظيف رقم الهوية
        if ($this->id_number) {
            $this->merge(['id_number' => preg_replace('/\D/', '', $this->id_number)]);
        }

        // تنظيف أرقام هوية التابعين
        if ($this->dependents && is_array($this->dependents)) {
            $dependents = $this->dependents;
            foreach ($dependents as $key => $dependent) {
                if (isset($dependent['id_number'])) {
                    $dependents[$key]['id_number'] = preg_replace('/\D/', '', $dependent['id_number']);
                }
            }
            $this->merge(['dependents' => $dependents]);
        }

        // حساب عدد التابعين تلقائياً
        if ($this->dependents && is_array($this->dependents)) {
            $this->merge(['dependents_count' => count($this->dependents)]);
        }
    }
}
