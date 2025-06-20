<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // تحديث أسماء المدن في جدول المشتركين
        $this->updateSubscribersCities();
        
        // تحديث أسماء المدن في جدول العملاء المحتملين
        $this->updatePotentialCustomersCities();
        
        // تحديث أسماء المدن في جدول المراكز الطبية
        $this->updateMedicalCentersCities();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // لا نحتاج إلى عكس هذا التحديث
        // لأنه تحديث لتوحيد أسماء المدن فقط
    }

    /**
     * تحديث أسماء المدن في جدول المشتركين
     */
    private function updateSubscribersCities(): void
    {
        $cityMappings = [
            // المنطقة الوسطى
            'وسط الرياض' => 'الرياض',
            'غرب الرياض' => 'الرياض',
            'شرق الرياض' => 'الرياض',
            'شمال الرياض' => 'الرياض',
            'جنوب الرياض' => 'الرياض',
            'محافظات الرياض' => 'الرياض',
            
            // المنطقة الغربية
            'مكة' => 'مكة المكرمة',
            'المدينة' => 'المدينة المنورة',
            
            // المنطقة الشرقية
            'الاحساء' => 'الأحساء',
            'القطيف' => 'القطيف',
            'الخفجي' => 'الخفجي',
            
            // المنطقة الشمالية
            'الجوف' => 'سكاكا',
            
            // المنطقة الجنوبية
            'عسير' => 'أبها',
            'نجران' => 'نجران',
            'جيزان' => 'جازان',
            'الباحة' => 'الباحة',
        ];

        foreach ($cityMappings as $oldName => $newName) {
            DB::table('subscribers')
                ->where('city', $oldName)
                ->update(['city' => $newName]);
        }
    }

    /**
     * تحديث أسماء المدن في جدول العملاء المحتملين
     */
    private function updatePotentialCustomersCities(): void
    {
        $cityMappings = [
            // المنطقة الوسطى
            'وسط الرياض' => 'الرياض',
            'غرب الرياض' => 'الرياض',
            'شرق الرياض' => 'الرياض',
            'شمال الرياض' => 'الرياض',
            'جنوب الرياض' => 'الرياض',
            'محافظات الرياض' => 'الرياض',
            
            // المنطقة الغربية
            'مكة' => 'مكة المكرمة',
            'المدينة' => 'المدينة المنورة',
            
            // المنطقة الشرقية
            'الاحساء' => 'الأحساء',
            'القطيف' => 'القطيف',
            'الخفجي' => 'الخفجي',
            
            // المنطقة الشمالية
            'الجوف' => 'سكاكا',
            
            // المنطقة الجنوبية
            'عسير' => 'أبها',
            'نجران' => 'نجران',
            'جيزان' => 'جازان',
            'الباحة' => 'الباحة',
        ];

        foreach ($cityMappings as $oldName => $newName) {
            DB::table('potential_customers')
                ->where('city', $oldName)
                ->update(['city' => $newName]);
        }
    }

    /**
     * تحديث أسماء المدن في جدول المراكز الطبية
     */
    private function updateMedicalCentersCities(): void
    {
        $cityMappings = [
            // المنطقة الوسطى
            'وسط الرياض' => 'الرياض',
            'غرب الرياض' => 'الرياض',
            'شرق الرياض' => 'الرياض',
            'شمال الرياض' => 'الرياض',
            'جنوب الرياض' => 'الرياض',
            'محافظات الرياض' => 'الرياض',
            
            // المنطقة الغربية
            'مكة' => 'مكة المكرمة',
            'المدينة' => 'المدينة المنورة',
            
            // المنطقة الشرقية
            'الاحساء' => 'الأحساء',
            'القطيف' => 'القطيف',
            'الخفجي' => 'الخفجي',
            
            // المنطقة الشمالية
            'الجوف' => 'سكاكا',
            
            // المنطقة الجنوبية
            'عسير' => 'أبها',
            'نجران' => 'نجران',
            'جيزان' => 'جازان',
            'الباحة' => 'الباحة',
        ];

        foreach ($cityMappings as $oldName => $newName) {
            DB::table('medical_centers')
                ->where('city', $oldName)
                ->update(['city' => $newName]);
        }
    }
};
