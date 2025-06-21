# دليل نشر نظام التكافل الصحي

## معلومات السيرفر
- **عنوان السيرفر**: 185.201.8.42
- **المستخدم**: root
- **كلمة المرور**: 8hHHf7Ld'FwHbI8r1hm
- **مسار النشر**: /root/public_html/new.altakafulalsehi.com
- **الدومين**: https://new.altakafulalsehi.com

## معلومات قاعدة البيانات
- **اسم قاعدة البيانات**: altakafulalsehi_new
- **مستخدم قاعدة البيانات**: altakafulalsehi_new
- **كلمة مرور قاعدة البيانات**: altakafulalsehi_new

## الملفات المطلوبة للنشر
1. `altakafulalsehi_production.zip` - ملف النظام المضغوط
2. `complete_deployment.sh` - سكريبت النشر الشامل
3. `setup_database.sql` - سكريبت إعداد قاعدة البيانات
4. `apache_vhost.conf` - تكوين Apache

## خطوات النشر

### الخطوة 1: رفع الملفات للسيرفر
```bash
# رفع ملف ZIP
scp altakafulalsehi_production.zip root@185.201.8.42:/root/

# رفع سكريبت النشر
scp complete_deployment.sh root@185.201.8.42:/root/

# رفع ملف إعداد قاعدة البيانات
scp setup_database.sql root@185.201.8.42:/root/

# رفع تكوين Apache
scp apache_vhost.conf root@185.201.8.42:/root/
```

### الخطوة 2: الاتصال بالسيرفر
```bash
ssh root@185.201.8.42
# كلمة المرور: 8hHHf7Ld'FwHbI8r1hm
```

### الخطوة 3: إعداد قاعدة البيانات
```bash
# تشغيل سكريبت إعداد قاعدة البيانات
mysql -u root -p < /root/setup_database.sql
```

### الخطوة 4: تشغيل سكريبت النشر
```bash
# جعل السكريبت قابل للتنفيذ
chmod +x /root/complete_deployment.sh

# تشغيل سكريبت النشر
/root/complete_deployment.sh
```

### الخطوة 5: تكوين Apache
```bash
# نسخ تكوين Apache
cp /root/apache_vhost.conf /etc/apache2/sites-available/new.altakafulalsehi.com.conf

# تفعيل الموقع
a2ensite new.altakafulalsehi.com.conf

# تفعيل الوحدات المطلوبة
a2enmod rewrite
a2enmod ssl
a2enmod headers

# إعادة تشغيل Apache
systemctl reload apache2
```

### الخطوة 6: التحقق من النشر
```bash
# فحص حالة Apache
systemctl status apache2

# فحص الموقع
curl -I https://new.altakafulalsehi.com

# فحص ملفات السجل
tail -f /var/log/apache2/new.altakafulalsehi.com_error.log
```

## الأوامر المفيدة للصيانة

### فحص السجلات
```bash
# سجلات Laravel
tail -f /root/public_html/new.altakafulalsehi.com/storage/logs/laravel.log

# سجلات Apache
tail -f /var/log/apache2/new.altakafulalsehi.com_error.log
tail -f /var/log/apache2/new.altakafulalsehi.com_access.log
```

### تحديث النظام
```bash
cd /root/public_html/new.altakafulalsehi.com

# تحديث Composer
composer install --optimize-autoloader --no-dev

# تحديث Laravel cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# تشغيل migrations جديدة
php artisan migrate --force
```

### النسخ الاحتياطي
```bash
# نسخ احتياطي لقاعدة البيانات
mysqldump -u altakafulalsehi_new -p altakafulalsehi_new > backup_$(date +%Y%m%d_%H%M%S).sql

# نسخ احتياطي للملفات
tar -czf backup_files_$(date +%Y%m%d_%H%M%S).tar.gz /root/public_html/new.altakafulalsehi.com
```

## استكشاف الأخطاء

### مشاكل الصلاحيات
```bash
chown -R www-data:www-data /root/public_html/new.altakafulalsehi.com
chmod -R 755 /root/public_html/new.altakafulalsehi.com
chmod -R 775 /root/public_html/new.altakafulalsehi.com/storage
chmod -R 775 /root/public_html/new.altakafulalsehi.com/bootstrap/cache
```

### مشاكل قاعدة البيانات
```bash
# فحص الاتصال بقاعدة البيانات
mysql -u altakafulalsehi_new -p altakafulalsehi_new

# إعادة تشغيل MySQL
systemctl restart mysql
```

### مشاكل Apache
```bash
# فحص تكوين Apache
apache2ctl configtest

# إعادة تشغيل Apache
systemctl restart apache2
```

## معلومات مهمة
- تأكد من وجود شهادة SSL للدومين
- راقب استخدام المساحة والذاكرة
- قم بعمل نسخ احتياطية دورية
- راقب سجلات الأخطاء بانتظام

## روابط مفيدة
- الموقع الرئيسي: https://new.altakafulalsehi.com
- لوحة الإدارة: https://new.altakafulalsehi.com/admin
- صفحة تسجيل الدخول: https://new.altakafulalsehi.com/login
