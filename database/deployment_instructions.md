# تعليمات نشر قاعدة البيانات - نظام التكافل الصحي

## 1. إعداد قاعدة البيانات على cPanel/WHM

### إنشاء قاعدة البيانات:
1. ادخل إلى cPanel
2. اذهب إلى "MySQL Databases"
3. أنشئ قاعدة بيانات جديدة باسم: `altakafulalsehi`
4. أنشئ مستخدم قاعدة بيانات جديد
5. اربط المستخدم بقاعدة البيانات مع جميع الصلاحيات

### تنفيذ ملف SQL:
```bash
# عبر phpMyAdmin
1. ادخل إلى phpMyAdmin
2. اختر قاعدة البيانات altakafulalsehi
3. اذهب إلى تبويب "Import"
4. ارفع ملف altakafulalsehi_mysql_database.sql
5. اضغط "Go"

# أو عبر MySQL Command Line
mysql -u username -p altakafulalsehi < altakafulalsehi_mysql_database.sql
```

## 2. تحديث ملف .env

```env
# إعدادات قاعدة البيانات
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=altakafulalsehi
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password

# إعدادات التطبيق
APP_NAME="Al Takaful Al Sehi"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# إعدادات PHP (للـ cPanel)
PHP_CLI_PATH=/opt/cpanel/ea-php82/root/usr/bin/php
```

## 3. أوامر النشر

```bash
# تحديث Composer
composer install --optimize-autoloader --no-dev

# تحديث الكاش
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ربط مجلد التخزين
php artisan storage:link

# ضبط الصلاحيات
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

## 4. بيانات تسجيل الدخول الافتراضية

```
البريد الإلكتروني: admin@altakafulalsehi.com
كلمة المرور: password
```

**⚠️ مهم: قم بتغيير كلمة المرور فور تسجيل الدخول الأول**

## 5. هيكل قاعدة البيانات

### الجداول الرئيسية:
- `users` - المستخدمين
- `roles` - الأدوار
- `permissions` - الصلاحيات
- `medical_centers` - المراكز الطبية
- `packages` - الباقات
- `subscribers` - المشتركين
- `dependents` - التابعين
- `payments` - المدفوعات
- `posts` - المقالات
- `offers` - العروض

### جداول النظام:
- `cache` - الكاش
- `sessions` - الجلسات
- `jobs` - الوظائف
- `migrations` - الهجرات

## 6. النسخ الاحتياطي

### إنشاء نسخة احتياطية:
```bash
mysqldump -u username -p altakafulalsehi > backup_$(date +%Y%m%d_%H%M%S).sql
```

### استعادة النسخة الاحتياطية:
```bash
mysql -u username -p altakafulalsehi < backup_file.sql
```

## 7. مراقبة الأداء

### فهارس مهمة:
- فهرس على `subscribers.card_number`
- فهرس على `subscribers.id_number`
- فهرس على `medical_centers.slug`
- فهرس على `posts.slug`

### تحسين الاستعلامات:
```sql
-- تحليل الجداول
ANALYZE TABLE subscribers, medical_centers, posts;

-- تحسين الجداول
OPTIMIZE TABLE subscribers, medical_centers, posts;
```

## 8. الأمان

### إعدادات MySQL الموصى بها:
```sql
-- تعطيل الوصول الخارجي لـ root
UPDATE mysql.user SET Host='localhost' WHERE User='root';

-- إنشاء مستخدم محدود الصلاحيات للتطبيق
CREATE USER 'altakaful_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT SELECT, INSERT, UPDATE, DELETE ON altakafulalsehi.* TO 'altakaful_user'@'localhost';
FLUSH PRIVILEGES;
```

### حماية الملفات:
```bash
# إخفاء ملف .env
chmod 600 .env

# حماية مجلد storage
chmod -R 755 storage/
chown -R www-data:www-data storage/
```

## 9. استكشاف الأخطاء

### مشاكل شائعة:

1. **خطأ في الاتصال بقاعدة البيانات:**
   - تحقق من إعدادات .env
   - تأكد من صحة بيانات المستخدم

2. **خطأ في الصلاحيات:**
   ```bash
   chmod -R 755 storage/
   chmod -R 755 bootstrap/cache/
   ```

3. **خطأ في الكاش:**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan view:clear
   ```

## 10. معلومات إضافية

- **إصدار PHP المطلوب:** 8.1 أو أحدث
- **إصدار MySQL المطلوب:** 5.7 أو أحدث / MariaDB 10.3 أو أحدث
- **الذاكرة المطلوبة:** 512MB على الأقل
- **مساحة القرص:** 1GB على الأقل

### روابط مفيدة:
- [Laravel Deployment Documentation](https://laravel.com/docs/deployment)
- [cPanel MySQL Documentation](https://docs.cpanel.net/cpanel/databases/mysql-databases/)
