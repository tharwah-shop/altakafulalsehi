# نظام المدن السعودية الجديد

## نظرة عامة

تم إنشاء نظام جديد لإدارة المدن السعودية خاص بالمشتركين والعملاء المحتملين. النظام الجديد يستخدم أسماء المدن الفعلية مثل "الرياض" و "جدة" و "الخرج" و "عرعر" بدلاً من النظام القديم المعقد الذي كان يستخدم مناطق فرعية مثل "غرب الرياض" و "شرق الرياض".

**ملاحظة مهمة:** صفحات الشبكة الطبية والمراكز الطبية ولوحة التحكم الخاصة بها تستمر في استخدام النظام القديم `config/cities.php` كما هو.

## الملفات الجديدة

### 1. ملف التكوين الجديد
- `config/saudi_cities.php` - يحتوي على جميع المدن السعودية بأسمائها العربية والإنجليزية

### 2. Helper Class الجديد
- `app/Helpers/SaudiCitiesHelper.php` - يوفر جميع الوظائف المطلوبة للتعامل مع المدن

### 3. Migration للتحديث
- `database/migrations/2025_06_20_120000_update_cities_to_saudi_cities_system.php` - يحدث البيانات الموجودة

## الوظائف المتاحة في SaudiCitiesHelper

```php
// الحصول على جميع المدن
SaudiCitiesHelper::getAllCities()

// الحصول على مدينة بالاسم العربي
SaudiCitiesHelper::getCityByName('الرياض')

// الحصول على مدينة بالاسم الإنجليزي
SaudiCitiesHelper::getCityBySlug('riyadh')

// الحصول على أسماء المدن فقط
SaudiCitiesHelper::getCityNames()

// التحقق من وجود المدينة
SaudiCitiesHelper::cityExists('جدة')

// البحث في المدن
SaudiCitiesHelper::searchCities('رياض')

// الحصول على المدن مرتبة أبجدياً
SaudiCitiesHelper::getCitiesSorted()

// الحصول على المدن الرئيسية
SaudiCitiesHelper::getMajorCities()

// تجميع المدن حسب المنطقة
SaudiCitiesHelper::getCitiesByRegion()
```

## الملفات المحدثة

### Controllers (للمشتركين والعملاء المحتملين فقط)
- `app/Http/Controllers/SubscriptionController.php`
- `app/Http/Controllers/CardRequestController.php`
- `app/Http/Controllers/Admin/SubscriberController.php`
- `app/Http/Controllers/Admin/PotentialCustomerController.php`

### Views (للمشتركين والعملاء المحتملين فقط)
- `resources/views/admin/subscribers/create.blade.php`
- `resources/views/admin/subscribers/edit.blade.php`
- `resources/views/admin/subscribers/export.blade.php`
- `resources/views/subscribe.blade.php`
- `resources/views/card-request.blade.php`
- `resources/views/admin/potential-customers/index.blade.php`

## الملفات التي تستمر في استخدام النظام القديم

### Controllers (الشبكة الطبية ولوحة التحكم)
- `app/Http/Controllers/Admin/MedicalCenterController.php` - يستخدم `CitiesHelper`
- `app/Http/Controllers/MedicalNetworkController.php` - يستخدم `CitiesHelper`

### Routes (الشبكة الطبية والمناطق)
- `routes/web.php` - routes المناطق والمدن للشبكة الطبية تستخدم `CitiesHelper`

## المدن المتاحة

النظام يحتوي على أكثر من 120 مدينة سعودية مقسمة على المناطق التالية:

### المنطقة الوسطى
الرياض، الخرج، المجمعة، المزاحمية، وادي الدواسر، الدوادمي، عفيف، القويعية، حوطة بني تميم، الأفلاج، السليل، ضرما، شقراء، رماح، ثادق، حريملاء

### منطقة القصيم
بريدة، عنيزة، الرس، المذنب، البكيرية، البدائع، رياض الخبراء، عيون الجواء، الأسياح، النبهانية، ضرية، عقلة الصقور

### المنطقة الغربية
جدة، مكة المكرمة، المدينة المنورة، الطائف، ينبع، رابغ، الليث، القنفذة، عسفان، أملج، الوجه، ضباء، تيماء، خيبر، العلا، بدر، المهد، الحناكية

### المنطقة الشرقية
الدمام، الخبر، الظهران، الأحساء، الجبيل، القطيف، حفر الباطن، سيهات، الخفجي، الصفوى، راس تنورة، بقيق، النعيرية، تاروت، العديد، قرية العليا

### المنطقة الشمالية
تبوك، حائل، عرعر، سكاكا، الجوف، طريف، طبرجل، القريات، رفحاء، العيساوية، الشنان، بقعاء، الغزالة، موقق، الشملي

### المنطقة الجنوبية
أبها، خميس مشيط، جازان، نجران، بيشة، الباحة، بلجرشي، محايل عسير، النماص، صبيا، أبو عريش، صامطة، الدرب، فرسان، الحرث، ضمد، بيش، العارضة، أحد رفيدة، ظهران الجنوب، سراة عبيدة، رجال ألمع، تنومة، بارق، المندق، قلوة، العقيق، المخواة، غامد الزناد، شرورة، حبونا، ثار، يدمة، خباش

## التغييرات في قاعدة البيانات

تم تحديث البيانات الموجودة في الجداول التالية:
- `subscribers` - تحديث عمود `city`
- `potential_customers` - تحديث عمود `city`
- `medical_centers` - تحديث عمود `city`

## مثال على الاستخدام في Views

```blade
<!-- في أي view -->
@foreach(\App\Helpers\SaudiCitiesHelper::getAllCities() as $city)
    <option value="{{ $city['name'] }}">{{ $city['name'] }}</option>
@endforeach

<!-- للمدن الرئيسية فقط -->
@foreach(\App\Helpers\SaudiCitiesHelper::getMajorCities() as $city)
    <option value="{{ $city['name'] }}">{{ $city['name'] }}</option>
@endforeach

<!-- مجمعة حسب المنطقة -->
@foreach(\App\Helpers\SaudiCitiesHelper::getCitiesByRegion() as $regionName => $cities)
    <optgroup label="{{ $regionName }}">
        @foreach($cities as $city)
            <option value="{{ $city['name'] }}">{{ $city['name'] }}</option>
        @endforeach
    </optgroup>
@endforeach
```

## ملاحظات مهمة

1. **التقسيم الواضح**:
   - **النظام الجديد** (`SaudiCitiesHelper`): للمشتركين والعملاء المحتملين فقط
   - **النظام القديم** (`CitiesHelper`): للشبكة الطبية والمراكز الطبية ولوحة التحكم
2. **التوافق مع البيانات الموجودة**: تم الحفاظ على التوافق مع البيانات الموجودة من خلال migration التحديث
3. **الأداء**: النظام الجديد أسرع وأبسط للمشتركين والعملاء المحتملين
4. **سهولة الصيانة**: إضافة مدن جديدة للمشتركين أصبح أسهل من خلال ملف التكوين
5. **عدم التأثير على الشبكة الطبية**: جميع صفحات الشبكة الطبية تعمل كما هي بدون تغيير

## الخطوات التالية

1. اختبار جميع الصفحات للتأكد من عمل النظام الجديد
2. تحديث أي ملفات أخرى قد تحتاج للتحديث
3. إزالة الملفات القديمة غير المستخدمة (اختياري)
4. تحديث الوثائق والتدريب للمستخدمين

## الدعم والصيانة

للإضافة أو التعديل على المدن، يتم التعديل في ملف `config/saudi_cities.php` فقط.
