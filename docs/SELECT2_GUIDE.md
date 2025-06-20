# دليل استخدام Select2 في نظام التكافل الصحي

## نظرة عامة

تم تكامل مكتبة Select2 في جميع أنحاء النظام لتحسين تجربة المستخدم مع عناصر الاختيار (select elements). يدعم النظام اللغة العربية والاتجاه من اليمين إلى اليسار (RTL) بشكل كامل.

## الميزات المتاحة

### 1. التهيئة التلقائية
- يتم تهيئة جميع عناصر `<select>` تلقائياً عند تحميل الصفحة
- دعم كامل للغة العربية والاتجاه RTL
- تصميم متوافق مع Bootstrap 5

### 2. البحث الذكي
- البحث متاح تلقائياً للقوائم التي تحتوي على أكثر من 10 خيارات
- يمكن تفعيل البحث يدوياً بإضافة class `searchable`

### 3. دعم المودال والـ Offcanvas
- يعمل بشكل صحيح داخل Bootstrap Modal
- يعمل بشكل صحيح داخل Bootstrap Offcanvas
- z-index محسن لتجنب مشاكل العرض

## كيفية الاستخدام

### الاستخدام الأساسي

```html
<select class="form-select" name="city">
    <option value="">اختر المدينة</option>
    <option value="riyadh">الرياض</option>
    <option value="jeddah">جدة</option>
    <option value="dammam">الدمام</option>
</select>
```

### تخصيص النص التوضيحي

```html
<select class="form-select" data-placeholder="اختر المدينة المناسبة">
    <option value="riyadh">الرياض</option>
    <option value="jeddah">جدة</option>
</select>
```

### تفعيل البحث

```html
<select class="form-select searchable" name="country">
    <option value="">اختر الدولة</option>
    <!-- قائمة طويلة من الدول -->
</select>
```

### منع التهيئة التلقائية

```html
<select class="form-select no-select2" name="simple">
    <option value="1">خيار 1</option>
    <option value="2">خيار 2</option>
</select>
```

### منع زر المسح

```html
<select class="form-select no-clear" name="required">
    <option value="1">خيار مطلوب</option>
    <option value="2">خيار آخر</option>
</select>
```

## الدوال المساعدة

### إعادة تهيئة عنصر محدد

```javascript
Select2Helper.reinitialize('#city-select');
```

### إضافة خيار جديد

```javascript
Select2Helper.addOption('#city-select', 'mecca', 'مكة المكرمة', true);
```

### إعادة تحميل الخيارات

```javascript
const newOptions = {
    'riyadh': 'الرياض',
    'jeddah': 'جدة',
    'dammam': 'الدمام'
};
Select2Helper.reloadOptions('#city-select', newOptions);
```

### تعيين قيمة

```javascript
Select2Helper.setValue('#city-select', 'riyadh');
```

### الحصول على النص المحدد

```javascript
const selectedText = Select2Helper.getSelectedText('#city-select');
```

## التخصيص المتقدم

### إضافة تكوين مخصص

```javascript
$('#special-select').select2({
    theme: 'bootstrap-5',
    language: 'ar',
    dir: 'rtl',
    placeholder: 'اختر خيار مخصص',
    allowClear: true,
    minimumResultsForSearch: 0,
    ajax: {
        url: '/api/search',
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,
                page: params.page
            };
        }
    }
});
```

### التعامل مع الأحداث

```javascript
$('#city-select').on('select2:select', function (e) {
    const data = e.params.data;
    console.log('تم اختيار:', data.text);
});

$('#city-select').on('select2:unselect', function (e) {
    console.log('تم إلغاء الاختيار');
});
```

## حالات الاستخدام الشائعة

### 1. قائمة المدن المعتمدة على المنطقة

```javascript
$('#region').on('change', function() {
    const region = $(this).val();
    
    // تحميل المدن بناءً على المنطقة
    $.get('/api/cities/' + region, function(cities) {
        Select2Helper.reloadOptions('#city', cities);
    });
});
```

### 2. البحث في قاعدة البيانات

```javascript
$('#medical-center').select2({
    ajax: {
        url: '/api/medical-centers/search',
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,
                type: $('#center-type').val()
            };
        },
        processResults: function (data) {
            return {
                results: data.map(function(item) {
                    return {
                        id: item.id,
                        text: item.name + ' - ' + item.city
                    };
                })
            };
        }
    }
});
```

## استكشاف الأخطاء

### مشكلة عدم ظهور القائمة المنسدلة

```javascript
// تأكد من أن العنصر الأب صحيح
$('#my-select').select2({
    dropdownParent: $('#my-modal') // للمودال
});
```

### مشكلة التصميم

```css
/* إضافة تصميم مخصص */
.my-custom-select2 .select2-selection {
    border: 2px solid #007bff;
}
```

### وضع التصحيح

```javascript
// تفعيل وضع التصحيح
// أضف ?debug=select2 إلى الرابط

// عرض جميع عناصر Select2
Select2Debug.listAll();

// فحص تكوين عنصر محدد
Select2Debug.checkConfig('#my-select');
```

## أفضل الممارسات

1. **استخدم النص التوضيحي المناسب**: تأكد من أن النص التوضيحي واضح ومفيد
2. **فعل البحث للقوائم الطويلة**: استخدم class `searchable` للقوائم التي تحتوي على أكثر من 10 عناصر
3. **اختبر في المودال**: تأكد من أن Select2 يعمل بشكل صحيح داخل المودال
4. **استخدم AJAX للبيانات الكبيرة**: لا تحمل آلاف الخيارات مرة واحدة
5. **اختبر على الأجهزة المحمولة**: تأكد من أن التصميم متجاوب

## الدعم والمساعدة

- للمشاكل التقنية: راجع console المتصفح للأخطاء
- للتخصيص: راجع ملف `public/css/select2-custom.css`
- للوظائف المتقدمة: راجع ملف `public/js/select2-init.js`

## الملفات ذات الصلة

- `public/js/select2-init.js` - ملف التهيئة الرئيسي
- `public/css/select2-custom.css` - ملف التصميم المخصص
- `resources/views/layouts/frontend.blade.php` - تخطيط الواجهة الأمامية
- `resources/views/layouts/admin.blade.php` - تخطيط لوحة الإدارة
