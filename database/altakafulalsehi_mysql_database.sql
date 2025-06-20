-- قاعدة بيانات نظام التكافل الصحي
-- Al Takaful Al Sehi Database Schema
-- Created: 2025-06-20

-- إنشاء قاعدة البيانات
CREATE DATABASE IF NOT EXISTS `altakafulalsehi` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `altakafulalsehi`;

-- جدول المستخدمين
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('admin','manager','employee') DEFAULT 'employee',
  `status` enum('active','inactive') DEFAULT 'active',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول الأدوار
CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول الصلاحيات
CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول ربط المستخدمين بالأدوار
CREATE TABLE `role_user` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_user_user_id_foreign` (`user_id`),
  KEY `role_user_role_id_foreign` (`role_id`),
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول ربط الأدوار بالصلاحيات
CREATE TABLE `permission_role` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permission_role_permission_id_foreign` (`permission_id`),
  KEY `permission_role_role_id_foreign` (`role_id`),
  CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول المراكز الطبية
CREATE TABLE `medical_centers` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `type` enum('مستشفى','مركز طبي','عيادة','مختبر','صيدلية','مركز أشعة') NOT NULL,
  `medical_service_types` json DEFAULT NULL,
  `medical_discounts` json DEFAULT NULL,
  `status` enum('active','inactive','pending') DEFAULT 'pending',
  `contract_status` enum('active','expired','pending','cancelled') DEFAULT 'pending',
  `contract_start_date` date DEFAULT NULL,
  `contract_end_date` date DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `images` json DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT 0.00,
  `reviews_count` int(11) DEFAULT 0,
  `views_count` int(11) DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `medical_centers_slug_unique` (`slug`),
  KEY `medical_centers_created_by_foreign` (`created_by`),
  CONSTRAINT `medical_centers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول تقييمات المراكز الطبية
CREATE TABLE `medical_center_reviews` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `medical_center_id` bigint(20) UNSIGNED NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `comment` text DEFAULT NULL,
  `status` enum('approved','pending','rejected') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `medical_center_reviews_medical_center_id_foreign` (`medical_center_id`),
  CONSTRAINT `medical_center_reviews_medical_center_id_foreign` FOREIGN KEY (`medical_center_id`) REFERENCES `medical_centers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول فئات المقالات
CREATE TABLE `post_categories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name_ar` varchar(255) NOT NULL,
  `name_en` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `description_ar` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `post_categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول المقالات
CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title_ar` varchar(255) NOT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `content_ar` longtext NOT NULL,
  `content_en` longtext DEFAULT NULL,
  `excerpt_ar` text DEFAULT NULL,
  `excerpt_en` text DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `medical_center_id` bigint(20) UNSIGNED NOT NULL,
  `author_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('published','draft','pending') DEFAULT 'draft',
  `is_featured` tinyint(1) DEFAULT 0,
  `views_count` int(11) DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `posts_slug_unique` (`slug`),
  KEY `posts_category_id_foreign` (`category_id`),
  KEY `posts_medical_center_id_foreign` (`medical_center_id`),
  KEY `posts_author_id_foreign` (`author_id`),
  CONSTRAINT `posts_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `posts_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `post_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `posts_medical_center_id_foreign` FOREIGN KEY (`medical_center_id`) REFERENCES `medical_centers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول مرفقات المقالات
CREATE TABLE `post_attachments` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `file_size` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `post_attachments_post_id_foreign` (`post_id`),
  CONSTRAINT `post_attachments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول العروض
CREATE TABLE `offers` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `medical_center_id` bigint(20) UNSIGNED NOT NULL,
  `discount_percentage` decimal(5,2) DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT NULL,
  `original_price` decimal(10,2) DEFAULT NULL,
  `discounted_price` decimal(10,2) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('active','inactive','expired','pending') DEFAULT 'pending',
  `image` varchar(255) DEFAULT NULL,
  `terms_conditions` text DEFAULT NULL,
  `max_uses` int(11) DEFAULT 0,
  `current_uses` int(11) DEFAULT 0,
  `is_featured` tinyint(1) DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `offers_slug_unique` (`slug`),
  KEY `offers_medical_center_id_foreign` (`medical_center_id`),
  KEY `offers_created_by_foreign` (`created_by`),
  KEY `offers_status_start_date_end_date_index` (`status`,`start_date`,`end_date`),
  KEY `offers_medical_center_id_status_index` (`medical_center_id`,`status`),
  KEY `offers_is_featured_index` (`is_featured`),
  CONSTRAINT `offers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `offers_medical_center_id_foreign` FOREIGN KEY (`medical_center_id`) REFERENCES `medical_centers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول الباقات
CREATE TABLE `packages` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `name_en` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `dependent_price` decimal(10,2) DEFAULT NULL,
  `duration_months` int(11) NOT NULL,
  `max_dependents` int(11) DEFAULT 0,
  `features` json DEFAULT NULL,
  `discount_percentage` decimal(5,2) DEFAULT 0.00,
  `is_active` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول المشتركين
CREATE TABLE `subscribers` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `id_number` varchar(20) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `card_number` varchar(20) NOT NULL,
  `package_id` bigint(20) UNSIGNED DEFAULT NULL,
  `card_price` decimal(10,2) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `dependents_count` int(11) DEFAULT 0,
  `status` enum('فعال','منتهي','ملغي','معلق','بانتظار الدفع','في انتظار التحقق من الدفع','معلق - مشكلة في الدفع') DEFAULT 'فعال',
  `discount_percentage` decimal(5,2) DEFAULT 0.00,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscribers_card_number_unique` (`card_number`),
  UNIQUE KEY `subscribers_id_number_unique` (`id_number`),
  KEY `subscribers_package_id_foreign` (`package_id`),
  KEY `subscribers_created_by_foreign` (`created_by`),
  CONSTRAINT `subscribers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `subscribers_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول التابعين
CREATE TABLE `dependents` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `subscriber_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `id_number` varchar(20) NOT NULL,
  `dependent_price` decimal(10,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dependents_subscriber_id_foreign` (`subscriber_id`),
  CONSTRAINT `dependents_subscriber_id_foreign` FOREIGN KEY (`subscriber_id`) REFERENCES `subscribers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول المدفوعات
CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `subscriber_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','bank_transfer','credit_card','online') NOT NULL,
  `payment_date` date NOT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `status` enum('completed','pending','failed','cancelled') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_subscriber_id_foreign` (`subscriber_id`),
  KEY `payments_created_by_foreign` (`created_by`),
  CONSTRAINT `payments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_subscriber_id_foreign` FOREIGN KEY (`subscriber_id`) REFERENCES `subscribers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول الاشتراكات المعلقة
CREATE TABLE `pending_subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `id_number` varchar(20) NOT NULL,
  `package_id` bigint(20) UNSIGNED DEFAULT NULL,
  `dependents_count` int(11) DEFAULT 0,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','approved','rejected','converted') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `processed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pending_subscriptions_package_id_foreign` (`package_id`),
  KEY `pending_subscriptions_created_by_foreign` (`created_by`),
  KEY `pending_subscriptions_processed_by_foreign` (`processed_by`),
  CONSTRAINT `pending_subscriptions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pending_subscriptions_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pending_subscriptions_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول العملاء المحتملين
CREATE TABLE `potential_customers` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `interested_package` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('new','contacted','interested','not_interested','converted') DEFAULT 'new',
  `source` varchar(100) DEFAULT NULL,
  `contacted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جداول النظام (Laravel)
-- جدول الكاش
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول الوظائف (Jobs)
CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(10) UNSIGNED DEFAULT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  `finished_at` int(10) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول إعادة تعيين كلمة المرور
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول الجلسات
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول الهجرات
CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- إدراج البيانات الأساسية (Seeders)
-- ========================================

-- إدراج الأدوار الأساسية
INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'مدير النظام', 'مدير النظام الرئيسي', NOW(), NOW()),
(2, 'manager', 'مدير', 'مدير القسم', NOW(), NOW()),
(3, 'employee', 'موظف', 'موظف عادي', NOW(), NOW());

-- إدراج الصلاحيات الأساسية
INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'users.view', 'عرض المستخدمين', 'عرض قائمة المستخدمين', NOW(), NOW()),
(2, 'users.create', 'إنشاء مستخدم', 'إنشاء مستخدم جديد', NOW(), NOW()),
(3, 'users.edit', 'تعديل المستخدمين', 'تعديل بيانات المستخدمين', NOW(), NOW()),
(4, 'users.delete', 'حذف المستخدمين', 'حذف المستخدمين', NOW(), NOW()),
(5, 'medical_centers.view', 'عرض المراكز الطبية', 'عرض قائمة المراكز الطبية', NOW(), NOW()),
(6, 'medical_centers.create', 'إنشاء مركز طبي', 'إنشاء مركز طبي جديد', NOW(), NOW()),
(7, 'medical_centers.edit', 'تعديل المراكز الطبية', 'تعديل بيانات المراكز الطبية', NOW(), NOW()),
(8, 'medical_centers.delete', 'حذف المراكز الطبية', 'حذف المراكز الطبية', NOW(), NOW()),
(9, 'subscribers.view', 'عرض المشتركين', 'عرض قائمة المشتركين', NOW(), NOW()),
(10, 'subscribers.create', 'إنشاء مشترك', 'إنشاء مشترك جديد', NOW(), NOW()),
(11, 'subscribers.edit', 'تعديل المشتركين', 'تعديل بيانات المشتركين', NOW(), NOW()),
(12, 'subscribers.delete', 'حذف المشتركين', 'حذف المشتركين', NOW(), NOW()),
(13, 'packages.view', 'عرض الباقات', 'عرض قائمة الباقات', NOW(), NOW()),
(14, 'packages.create', 'إنشاء باقة', 'إنشاء باقة جديدة', NOW(), NOW()),
(15, 'packages.edit', 'تعديل الباقات', 'تعديل بيانات الباقات', NOW(), NOW()),
(16, 'packages.delete', 'حذف الباقات', 'حذف الباقات', NOW(), NOW()),
(17, 'posts.view', 'عرض المقالات', 'عرض قائمة المقالات', NOW(), NOW()),
(18, 'posts.create', 'إنشاء مقال', 'إنشاء مقال جديد', NOW(), NOW()),
(19, 'posts.edit', 'تعديل المقالات', 'تعديل بيانات المقالات', NOW(), NOW()),
(20, 'posts.delete', 'حذف المقالات', 'حذف المقالات', NOW(), NOW()),
(21, 'offers.view', 'عرض العروض', 'عرض قائمة العروض', NOW(), NOW()),
(22, 'offers.create', 'إنشاء عرض', 'إنشاء عرض جديد', NOW(), NOW()),
(23, 'offers.edit', 'تعديل العروض', 'تعديل بيانات العروض', NOW(), NOW()),
(24, 'offers.delete', 'حذف العروض', 'حذف العروض', NOW(), NOW()),
(25, 'payments.view', 'عرض المدفوعات', 'عرض قائمة المدفوعات', NOW(), NOW()),
(26, 'payments.create', 'إنشاء دفعة', 'إنشاء دفعة جديدة', NOW(), NOW()),
(27, 'payments.edit', 'تعديل المدفوعات', 'تعديل بيانات المدفوعات', NOW(), NOW()),
(28, 'payments.delete', 'حذف المدفوعات', 'حذف المدفوعات', NOW(), NOW()),
(29, 'reports.view', 'عرض التقارير', 'عرض التقارير والإحصائيات', NOW(), NOW()),
(30, 'settings.manage', 'إدارة الإعدادات', 'إدارة إعدادات النظام', NOW(), NOW());

-- ربط الأدوار بالصلاحيات
-- مدير النظام: جميع الصلاحيات
INSERT INTO `permission_role` (`permission_id`, `role_id`, `created_at`, `updated_at`)
SELECT id, 1, NOW(), NOW() FROM `permissions`;

-- المدير: معظم الصلاحيات عدا إدارة المستخدمين والإعدادات
INSERT INTO `permission_role` (`permission_id`, `role_id`, `created_at`, `updated_at`)
SELECT id, 2, NOW(), NOW() FROM `permissions` WHERE id NOT IN (2, 4, 30);

-- الموظف: صلاحيات العرض والإنشاء فقط
INSERT INTO `permission_role` (`permission_id`, `role_id`, `created_at`, `updated_at`)
SELECT id, 3, NOW(), NOW() FROM `permissions` WHERE name LIKE '%.view' OR name LIKE '%.create';

-- إنشاء مستخدم مدير النظام الافتراضي
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `status`, `email_verified_at`, `created_at`, `updated_at`) VALUES
(1, 'مدير النظام', 'admin@altakafulalsehi.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '966500000000', 'admin', 'active', NOW(), NOW(), NOW());

-- ربط المدير بدور مدير النظام
INSERT INTO `role_user` (`user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 1, NOW(), NOW());

-- إدراج فئات المقالات الأساسية
INSERT INTO `post_categories` (`id`, `name_ar`, `name_en`, `slug`, `description_ar`, `description_en`, `status`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'أخبار طبية', 'Medical News', 'medical-news', 'آخر الأخبار والمستجدات الطبية', 'Latest medical news and updates', 'active', 1, NOW(), NOW()),
(2, 'نصائح صحية', 'Health Tips', 'health-tips', 'نصائح وإرشادات للحفاظ على الصحة', 'Tips and guidelines for maintaining health', 'active', 2, NOW(), NOW()),
(3, 'التغذية', 'Nutrition', 'nutrition', 'مقالات حول التغذية الصحية', 'Articles about healthy nutrition', 'active', 3, NOW(), NOW()),
(4, 'الوقاية', 'Prevention', 'prevention', 'طرق الوقاية من الأمراض', 'Disease prevention methods', 'active', 4, NOW(), NOW()),
(5, 'الصحة النفسية', 'Mental Health', 'mental-health', 'مقالات حول الصحة النفسية والعقلية', 'Articles about mental and psychological health', 'active', 5, NOW(), NOW());

-- إدراج الباقات الأساسية
INSERT INTO `packages` (`id`, `name`, `name_en`, `description`, `description_en`, `price`, `dependent_price`, `duration_months`, `max_dependents`, `features`, `discount_percentage`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'الباقة الأساسية', 'Basic Package', 'باقة أساسية تشمل الخدمات الطبية الأساسية', 'Basic package including essential medical services', 500.00, 100.00, 12, 5, '["فحص طبي شامل", "استشارات طبية", "خصومات على الأدوية", "خصومات على التحاليل"]', 0.00, 1, 1, NOW(), NOW()),
(2, 'الباقة المتوسطة', 'Standard Package', 'باقة متوسطة تشمل خدمات إضافية', 'Standard package with additional services', 800.00, 150.00, 12, 7, '["فحص طبي شامل", "استشارات طبية", "خصومات على الأدوية", "خصومات على التحاليل", "خصومات على الأشعة", "استشارات متخصصة"]', 5.00, 1, 2, NOW(), NOW()),
(3, 'الباقة المميزة', 'Premium Package', 'باقة مميزة تشمل جميع الخدمات', 'Premium package with all services included', 1200.00, 200.00, 12, 10, '["فحص طبي شامل", "استشارات طبية", "خصومات على الأدوية", "خصومات على التحاليل", "خصومات على الأشعة", "استشارات متخصصة", "خدمات طوارئ", "خصومات على العمليات"]', 10.00, 1, 3, NOW(), NOW()),
(4, 'باقة العائلة', 'Family Package', 'باقة خاصة للعائلات الكبيرة', 'Special package for large families', 2000.00, 120.00, 12, 15, '["فحص طبي شامل للعائلة", "استشارات طبية", "خصومات على الأدوية", "خصومات على التحاليل", "خصومات على الأشعة", "استشارات متخصصة", "خدمات طوارئ"]', 15.00, 1, 4, NOW(), NOW());

-- إدراج بيانات الهجرات
INSERT INTO `migrations` (`migration`, `batch`) VALUES
('0001_01_01_000000_create_users_table', 1),
('0001_01_01_000001_create_cache_table', 1),
('0001_01_01_000002_create_jobs_table', 1),
('2024_01_01_000000_create_potential_customers_table', 1),
('2025_06_18_184958_create_roles_table', 1),
('2025_06_18_185006_create_permissions_table', 1),
('2025_06_18_185124_create_role_user_table', 1),
('2025_06_18_185232_add_columns_to_users_table', 1),
('2025_06_18_185232_create_permission_role_table', 1),
('2025_06_18_185644_create_medical_centers_table', 1),
('2025_06_18_185741_create_post_categories_table', 1),
('2025_06_18_185741_create_posts_table', 1),
('2025_06_18_185742_create_post_attachments_table', 1),
('2025_06_18_190841_create_medical_center_reviews_table', 1),
('2025_06_18_200000_add_city_id_to_medical_centers_table', 1),
('2025_06_18_210000_add_contract_fields_to_medical_centers_table', 1),
('2025_06_18_220000_update_medical_centers_type_field', 1),
('2025_06_18_240000_create_offers_table', 1),
('2025_06_19_000000_remove_columns_from_medical_centers_table', 1),
('2025_06_19_145025_create_packages_table', 1),
('2025_06_19_145121_create_subscribers_table', 1),
('2025_06_19_145227_create_dependents_table', 1),
('2025_06_20_053854_create_payments_table', 1),
('2025_06_20_060819_create_pending_subscriptions_table', 1),
('2025_06_20_102145_remove_utm_fields_from_subscribers_table', 1),
('2025_06_20_105925_update_medical_centers_remove_foreign_keys', 1),
('2025_06_20_105950_update_subscribers_remove_city_foreign_key', 1),
('2025_06_20_111451_drop_cities_and_regions_tables', 1),
('2025_06_20_120000_update_cities_to_saudi_cities_system', 1),
('2025_06_20_122955_remove_unwanted_fields_from_medical_centers_table', 1),
('2025_06_20_130556_update_potential_customers_table_for_new_city_system', 1);

-- ========================================
-- ملاحظات مهمة للنشر
-- ========================================
/*
1. تأكد من تحديث ملف .env بإعدادات قاعدة البيانات الصحيحة:
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=altakafulalsehi
   DB_USERNAME=your_username
   DB_PASSWORD=your_password

2. كلمة مرور المدير الافتراضية: password
   يُنصح بتغييرها فور تسجيل الدخول

3. تأكد من تشغيل الأوامر التالية بعد إنشاء قاعدة البيانات:
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan storage:link

4. للنشر على cPanel/WHM:
   - استخدم phpMyAdmin أو MySQL Command Line لتنفيذ هذا الملف
   - تأكد من أن إصدار MySQL/MariaDB يدعم JSON data type
   - قم بضبط صلاحيات المجلدات storage و bootstrap/cache

5. النسخ الاحتياطي:
   - قم بعمل نسخة احتياطية دورية لقاعدة البيانات
   - استخدم mysqldump لإنشاء النسخ الاحتياطية
*/
