# اختبارات نظام إدارة الصور للمراكز الطبية

هذا المجلد يحتوي على اختبارات شاملة لنظام إدارة الصور في المراكز الطبية.

## الاختبارات المتوفرة

### 1. اختبارات الوحدة (Unit Tests)

#### ImageHelperTest.php
اختبارات لفئة `ImageHelper` التي تتعامل مع رفع وإدارة الصور:

- ✅ التحقق من صحة الصور
- ✅ رفض الملفات غير الصحيحة
- ✅ رفض الملفات كبيرة الحجم
- ✅ رفع وتحسين الصور
- ✅ إنشاء الصور المصغرة
- ✅ حذف الصور مع الصور المصغرة
- ✅ الحصول على معلومات الصور
- ✅ رفع متعدد للصور
- ✅ حذف متعدد للصور

### 2. اختبارات الميزات (Feature Tests)

#### MedicalCenterImageTest.php
اختبارات لإدارة الصور في المراكز الطبية من خلال لوحة التحكم:

- ✅ إنشاء مركز طبي مع صورة
- ✅ تحديث صورة المركز الطبي
- ✅ إزالة صورة المركز الطبي
- ✅ رفض الصور غير الصحيحة
- ✅ رفض الصور كبيرة الحجم
- ✅ خصائص الصور في النموذج
- ✅ حذف الصور عند حذف المركز

#### MedicalCenterFrontendTest.php
اختبارات لعرض الصور في الواجهة الأمامية:

- ✅ عرض الصور في صفحة القائمة
- ✅ عرض الصور في صفحة التفاصيل
- ✅ التعامل مع الصور المفقودة
- ✅ عرض الصور المصغرة
- ✅ تأثيرات التحويم
- ✅ معالجة أخطاء الصور
- ✅ البحث مع الصور
- ✅ أزرار التواصل
- ✅ عرض التقييمات

## تشغيل الاختبارات

### تشغيل جميع الاختبارات
```bash
php artisan test
```

### تشغيل اختبارات محددة

#### اختبارات ImageHelper فقط
```bash
php artisan test tests/Unit/ImageHelperTest.php
```

#### اختبارات إدارة الصور فقط
```bash
php artisan test tests/Feature/MedicalCenterImageTest.php
```

#### اختبارات الواجهة الأمامية فقط
```bash
php artisan test tests/Feature/MedicalCenterFrontendTest.php
```

### تشغيل اختبارات مع تفاصيل
```bash
php artisan test --verbose
```

### تشغيل اختبارات مع تقرير التغطية
```bash
php artisan test --coverage
```

## متطلبات الاختبارات

### 1. إعداد قاعدة البيانات
تأكد من وجود قاعدة بيانات اختبار في ملف `.env.testing`:

```env
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

أو استخدم قاعدة بيانات منفصلة:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=altakafulalsehi_test
DB_USERNAME=root
DB_PASSWORD=
```

### 2. إعداد التخزين
الاختبارات تستخدم `Storage::fake('public')` لمحاكاة نظام الملفات.

### 3. المتطلبات الإضافية
- PHP GD أو Imagick لمعالجة الصور
- مكتبة Intervention Image

## هيكل الاختبارات

```
tests/
├── Feature/
│   ├── MedicalCenterImageTest.php      # اختبارات إدارة الصور
│   └── MedicalCenterFrontendTest.php   # اختبارات الواجهة الأمامية
├── Unit/
│   └── ImageHelperTest.php             # اختبارات ImageHelper
└── README.md                           # هذا الملف
```

## Factory المستخدمة

### MedicalCenterFactory
تم إنشاء Factory للمراكز الطبية مع الحالات التالية:

- `active()` - مركز نشط
- `inactive()` - مركز غير نشط
- `withImage()` - مركز مع صورة
- `withoutImage()` - مركز بدون صورة
- `highRated()` - مركز بتقييم عالي
- `lowRated()` - مركز بتقييم منخفض
- `inRiyadh()` - مركز في الرياض
- `inJeddah()` - مركز في جدة
- `generalHospital()` - مستشفى عام
- `specialtyClinic()` - عيادة تخصصية

### استخدام Factory
```php
// إنشاء مركز طبي نشط مع صورة
$center = MedicalCenter::factory()->active()->withImage()->create();

// إنشاء مستشفى عام في الرياض
$hospital = MedicalCenter::factory()->generalHospital()->inRiyadh()->create();
```

## نصائح للاختبار

1. **تشغيل الاختبارات بانتظام**: قم بتشغيل الاختبارات قبل كل commit
2. **إضافة اختبارات جديدة**: عند إضافة ميزات جديدة، أضف اختبارات مناسبة
3. **مراقبة التغطية**: تأكد من أن التغطية عالية (>80%)
4. **اختبار الحالات الحدية**: اختبر الحالات الاستثنائية والأخطاء

## استكشاف الأخطاء

### خطأ في قاعدة البيانات
```bash
php artisan migrate:fresh --env=testing
```

### خطأ في الصور
تأكد من تثبيت مكتبة معالجة الصور:
```bash
composer require intervention/image
```

### خطأ في الأذونات
تأكد من أذونات مجلد storage:
```bash
chmod -R 755 storage/
```

## التحديثات المستقبلية

- [ ] إضافة اختبارات للصور المتعددة
- [ ] اختبارات الأداء للصور الكبيرة
- [ ] اختبارات التكامل مع CDN
- [ ] اختبارات الأمان للرفع
