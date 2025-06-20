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
        Schema::table('potential_customers', function (Blueprint $table) {
            // إزالة الفهارس المرتبطة بـ city_id أولاً
            $table->dropIndex(['city_id', 'created_at']);

            // إزالة العلاقة مع جدول المدن
            $table->dropForeign(['city_id']);
            $table->dropColumn('city_id');

            // إضافة عمود المدينة كنص
            $table->string('city')->nullable()->after('phone');

            // إضافة حقول تتبع إضافية
            $table->string('referrer_url')->nullable()->after('user_agent');
            $table->string('landing_page')->nullable()->after('referrer_url');
            $table->string('utm_source')->nullable()->after('landing_page');
            $table->string('utm_medium')->nullable()->after('utm_source');
            $table->string('utm_campaign')->nullable()->after('utm_medium');
            $table->string('utm_term')->nullable()->after('utm_campaign');
            $table->string('utm_content')->nullable()->after('utm_term');

            // إضافة فهارس جديدة
            $table->index('city');
            $table->index('utm_source');
            $table->index('utm_medium');
        });

        // تحديث أسماء المدن بعد إنشاء العمود
        $this->updatePotentialCustomersCities();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('potential_customers', function (Blueprint $table) {
            // إزالة الحقول الجديدة
            $table->dropIndex(['city']);
            $table->dropIndex(['utm_source']);
            $table->dropIndex(['utm_medium']);

            $table->dropColumn([
                'city',
                'referrer_url',
                'landing_page',
                'utm_source',
                'utm_medium',
                'utm_campaign',
                'utm_term',
                'utm_content'
            ]);

            // إعادة إضافة العلاقة مع جدول المدن
            $table->foreignId('city_id')->nullable()->constrained()->onDelete('set null')->after('phone');
        });
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
};
