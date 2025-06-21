<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // تحسين فهارس جدول المراكز الطبية
        Schema::table('medical_centers', function (Blueprint $table) {
            // فهارس مركبة للبحث والفلترة
            $table->index(['status', 'type', 'city'], 'idx_medical_centers_search');
            $table->index(['contract_status', 'contract_end_date'], 'idx_medical_centers_contract');
            $table->index(['rating', 'reviews_count'], 'idx_medical_centers_rating');
            $table->index(['created_at', 'status'], 'idx_medical_centers_recent');
            $table->index(['region', 'city', 'type'], 'idx_medical_centers_location');
            
            // فهرس للبحث النصي
            $table->index('name', 'idx_medical_centers_name');
            $table->index('slug', 'idx_medical_centers_slug');
        });

        // تحسين فهارس جدول المشتركين
        Schema::table('subscribers', function (Blueprint $table) {
            // فهارس مركبة للبحث والفلترة
            $table->index(['status', 'end_date'], 'idx_subscribers_status_expiry');
            $table->index(['package_id', 'status', 'start_date'], 'idx_subscribers_package');
            $table->index(['nationality', 'city'], 'idx_subscribers_demographics');
            $table->index(['created_at', 'status'], 'idx_subscribers_recent');
            
            // فهارس للبحث السريع
            $table->index('name', 'idx_subscribers_name');
            $table->index(['start_date', 'end_date'], 'idx_subscribers_dates');
        });

        // تحسين فهارس جدول العروض
        Schema::table('offers', function (Blueprint $table) {
            // فهارس مركبة للعروض النشطة
            $table->index(['status', 'start_date', 'end_date'], 'idx_offers_active');
            $table->index(['medical_center_id', 'status', 'is_featured'], 'idx_offers_center');
            $table->index(['is_featured', 'status', 'start_date'], 'idx_offers_featured');
            $table->index(['created_at', 'status'], 'idx_offers_recent');
        });

        // تحسين فهارس جدول المقالات
        Schema::table('posts', function (Blueprint $table) {
            // فهارس مركبة للمقالات
            $table->index(['status', 'published_at'], 'idx_posts_published');
            $table->index(['category_id', 'status', 'is_featured'], 'idx_posts_category');
            $table->index(['medical_center_id', 'status'], 'idx_posts_center');
            $table->index(['is_featured', 'status', 'published_at'], 'idx_posts_featured');
            $table->index(['author_id', 'status'], 'idx_posts_author');
            
            // فهرس للبحث النصي
            $table->index('slug', 'idx_posts_slug');
        });

        // تحسين فهارس جدول الباقات
        Schema::table('packages', function (Blueprint $table) {
            // فهارس للباقات
            $table->index(['status', 'is_featured'], 'idx_packages_status');
            $table->index(['price', 'status'], 'idx_packages_price');
            $table->index(['duration_months', 'status'], 'idx_packages_duration');
        });

        // تحسين فهارس جدول المدفوعات
        Schema::table('payments', function (Blueprint $table) {
            // فهارس مركبة للمدفوعات
            $table->index(['status', 'payment_method', 'created_at'], 'idx_payments_status');
            $table->index(['subscriber_id', 'status'], 'idx_payments_subscriber');
            $table->index(['verified_at', 'status'], 'idx_payments_verified');
            $table->index(['transfer_confirmed_at', 'status'], 'idx_payments_confirmed');
        });

        // تحسين فهارس جدول العملاء المحتملين
        Schema::table('potential_customers', function (Blueprint $table) {
            // فهارس مركبة للعملاء المحتملين
            $table->index(['status', 'created_at'], 'idx_potential_status');
            $table->index(['source', 'device_type', 'created_at'], 'idx_potential_tracking');
            $table->index(['city', 'status'], 'idx_potential_location');
            $table->index(['utm_source', 'utm_medium', 'created_at'], 'idx_potential_utm');
        });

        // تحسين فهارس جدول التابعين
        Schema::table('dependents', function (Blueprint $table) {
            // فهارس للتابعين
            $table->index(['subscriber_id', 'nationality'], 'idx_dependents_subscriber');
            $table->index('id_number', 'idx_dependents_id_number');
        });

        // تحسين فهارس جدول الاشتراكات المعلقة
        Schema::table('pending_subscriptions', function (Blueprint $table) {
            // فهارس للاشتراكات المعلقة
            $table->index(['session_id', 'created_at'], 'idx_pending_session');
            $table->index(['package_id', 'payment_method'], 'idx_pending_package');
            $table->index(['phone', 'id_number'], 'idx_pending_contact');
            $table->index('created_at', 'idx_pending_created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إزالة الفهارس المضافة
        Schema::table('medical_centers', function (Blueprint $table) {
            $table->dropIndex('idx_medical_centers_search');
            $table->dropIndex('idx_medical_centers_contract');
            $table->dropIndex('idx_medical_centers_rating');
            $table->dropIndex('idx_medical_centers_recent');
            $table->dropIndex('idx_medical_centers_location');
            $table->dropIndex('idx_medical_centers_name');
            $table->dropIndex('idx_medical_centers_slug');
        });

        Schema::table('subscribers', function (Blueprint $table) {
            $table->dropIndex('idx_subscribers_status_expiry');
            $table->dropIndex('idx_subscribers_package');
            $table->dropIndex('idx_subscribers_demographics');
            $table->dropIndex('idx_subscribers_recent');
            $table->dropIndex('idx_subscribers_name');
            $table->dropIndex('idx_subscribers_dates');
        });

        Schema::table('offers', function (Blueprint $table) {
            $table->dropIndex('idx_offers_active');
            $table->dropIndex('idx_offers_center');
            $table->dropIndex('idx_offers_featured');
            $table->dropIndex('idx_offers_recent');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex('idx_posts_published');
            $table->dropIndex('idx_posts_category');
            $table->dropIndex('idx_posts_center');
            $table->dropIndex('idx_posts_featured');
            $table->dropIndex('idx_posts_author');
            $table->dropIndex('idx_posts_slug');
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->dropIndex('idx_packages_status');
            $table->dropIndex('idx_packages_price');
            $table->dropIndex('idx_packages_duration');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('idx_payments_status');
            $table->dropIndex('idx_payments_subscriber');
            $table->dropIndex('idx_payments_verified');
            $table->dropIndex('idx_payments_confirmed');
        });

        Schema::table('potential_customers', function (Blueprint $table) {
            $table->dropIndex('idx_potential_status');
            $table->dropIndex('idx_potential_tracking');
            $table->dropIndex('idx_potential_location');
            $table->dropIndex('idx_potential_utm');
        });

        Schema::table('dependents', function (Blueprint $table) {
            $table->dropIndex('idx_dependents_subscriber');
            $table->dropIndex('idx_dependents_id_number');
        });

        Schema::table('pending_subscriptions', function (Blueprint $table) {
            $table->dropIndex('idx_pending_session');
            $table->dropIndex('idx_pending_package');
            $table->dropIndex('idx_pending_contact');
            $table->dropIndex('idx_pending_created');
        });
    }
};
