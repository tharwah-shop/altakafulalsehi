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
        // إضافة قيود التحقق لجدول المراكز الطبية
        $this->addMedicalCenterConstraints();
        
        // إضافة قيود التحقق لجدول المشتركين
        $this->addSubscriberConstraints();
        
        // إضافة قيود التحقق لجدول العروض
        $this->addOfferConstraints();
        
        // إضافة قيود التحقق لجدول المدفوعات
        $this->addPaymentConstraints();
        
        // إضافة قيود التحقق لجدول الباقات
        $this->addPackageConstraints();
    }

    /**
     * إضافة قيود التحقق لجدول المراكز الطبية
     */
    private function addMedicalCenterConstraints(): void
    {
        DB::statement('ALTER TABLE medical_centers ADD CONSTRAINT chk_medical_center_type CHECK (type BETWEEN 1 AND 12)');
        DB::statement('ALTER TABLE medical_centers ADD CONSTRAINT chk_medical_center_rating CHECK (rating BETWEEN 0 AND 5)');
        DB::statement('ALTER TABLE medical_centers ADD CONSTRAINT chk_medical_center_reviews_count CHECK (reviews_count >= 0)');
        DB::statement('ALTER TABLE medical_centers ADD CONSTRAINT chk_medical_center_views_count CHECK (views_count >= 0)');
        DB::statement('ALTER TABLE medical_centers ADD CONSTRAINT chk_medical_center_latitude CHECK (latitude BETWEEN -90 AND 90)');
        DB::statement('ALTER TABLE medical_centers ADD CONSTRAINT chk_medical_center_longitude CHECK (longitude BETWEEN -180 AND 180)');
        DB::statement('ALTER TABLE medical_centers ADD CONSTRAINT chk_medical_center_phone CHECK (phone REGEXP "^(\\+966|0)?[5][0-9]{8}$" OR phone IS NULL)');
        DB::statement('ALTER TABLE medical_centers ADD CONSTRAINT chk_medical_center_email CHECK (email REGEXP "^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$" OR email IS NULL)');
        DB::statement('ALTER TABLE medical_centers ADD CONSTRAINT chk_medical_center_contract_dates CHECK (contract_start_date IS NULL OR contract_end_date IS NULL OR contract_start_date <= contract_end_date)');
    }

    /**
     * إضافة قيود التحقق لجدول المشتركين
     */
    private function addSubscriberConstraints(): void
    {
        DB::statement('ALTER TABLE subscribers ADD CONSTRAINT chk_subscriber_phone CHECK (phone REGEXP "^(\\+966|0)?[5][0-9]{8}$")');
        DB::statement('ALTER TABLE subscribers ADD CONSTRAINT chk_subscriber_id_number CHECK (id_number REGEXP "^[12][0-9]{9}$")');
        DB::statement('ALTER TABLE subscribers ADD CONSTRAINT chk_subscriber_email CHECK (email REGEXP "^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$" OR email IS NULL)');
        DB::statement('ALTER TABLE subscribers ADD CONSTRAINT chk_subscriber_dates CHECK (start_date <= end_date)');
        DB::statement('ALTER TABLE subscribers ADD CONSTRAINT chk_subscriber_card_price CHECK (card_price >= 0)');
        DB::statement('ALTER TABLE subscribers ADD CONSTRAINT chk_subscriber_total_amount CHECK (total_amount >= 0)');
        DB::statement('ALTER TABLE subscribers ADD CONSTRAINT chk_subscriber_dependents_count CHECK (dependents_count >= 0 AND dependents_count <= 20)');
        DB::statement('ALTER TABLE subscribers ADD CONSTRAINT chk_subscriber_discount_percentage CHECK (discount_percentage >= 0 AND discount_percentage <= 100)');
        DB::statement('ALTER TABLE subscribers ADD CONSTRAINT chk_subscriber_discount_amount CHECK (discount_amount >= 0)');
    }

    /**
     * إضافة قيود التحقق لجدول العروض
     */
    private function addOfferConstraints(): void
    {
        DB::statement('ALTER TABLE offers ADD CONSTRAINT chk_offer_discount_percentage CHECK (discount_percentage IS NULL OR (discount_percentage >= 0 AND discount_percentage <= 100))');
        DB::statement('ALTER TABLE offers ADD CONSTRAINT chk_offer_discount_amount CHECK (discount_amount IS NULL OR discount_amount >= 0)');
        DB::statement('ALTER TABLE offers ADD CONSTRAINT chk_offer_original_price CHECK (original_price IS NULL OR original_price >= 0)');
        DB::statement('ALTER TABLE offers ADD CONSTRAINT chk_offer_discounted_price CHECK (discounted_price IS NULL OR discounted_price >= 0)');
        DB::statement('ALTER TABLE offers ADD CONSTRAINT chk_offer_dates CHECK (start_date <= end_date)');
        DB::statement('ALTER TABLE offers ADD CONSTRAINT chk_offer_max_uses CHECK (max_uses >= 0)');
        DB::statement('ALTER TABLE offers ADD CONSTRAINT chk_offer_current_uses CHECK (current_uses >= 0)');
        DB::statement('ALTER TABLE offers ADD CONSTRAINT chk_offer_uses_limit CHECK (current_uses <= max_uses OR max_uses = 0)');
    }

    /**
     * إضافة قيود التحقق لجدول المدفوعات
     */
    private function addPaymentConstraints(): void
    {
        DB::statement('ALTER TABLE payments ADD CONSTRAINT chk_payment_amount CHECK (amount > 0)');
        DB::statement('ALTER TABLE payments ADD CONSTRAINT chk_payment_transfer_amount CHECK (transfer_amount IS NULL OR transfer_amount > 0)');
        DB::statement('ALTER TABLE payments ADD CONSTRAINT chk_payment_verification_dates CHECK (verified_at IS NULL OR transfer_confirmed_at IS NULL OR verified_at >= transfer_confirmed_at)');
    }

    /**
     * إضافة قيود التحقق لجدول الباقات
     */
    private function addPackageConstraints(): void
    {
        DB::statement('ALTER TABLE packages ADD CONSTRAINT chk_package_price CHECK (price >= 0)');
        DB::statement('ALTER TABLE packages ADD CONSTRAINT chk_package_dependent_price CHECK (dependent_price IS NULL OR dependent_price >= 0)');
        DB::statement('ALTER TABLE packages ADD CONSTRAINT chk_package_duration_months CHECK (duration_months > 0 AND duration_months <= 120)');
        DB::statement('ALTER TABLE packages ADD CONSTRAINT chk_package_max_dependents CHECK (max_dependents >= 0 AND max_dependents <= 50)');
        DB::statement('ALTER TABLE packages ADD CONSTRAINT chk_package_discount_percentage CHECK (discount_percentage >= 0 AND discount_percentage <= 100)');
    }

    /**
     * إضافة قيود التحقق لجدول التابعين
     */
    private function addDependentConstraints(): void
    {
        DB::statement('ALTER TABLE dependents ADD CONSTRAINT chk_dependent_id_number CHECK (id_number IS NULL OR id_number REGEXP "^[12][0-9]{9}$")');
        DB::statement('ALTER TABLE dependents ADD CONSTRAINT chk_dependent_price CHECK (dependent_price IS NULL OR dependent_price >= 0)');
    }

    /**
     * إضافة قيود التحقق لجدول العملاء المحتملين
     */
    private function addPotentialCustomerConstraints(): void
    {
        DB::statement('ALTER TABLE potential_customers ADD CONSTRAINT chk_potential_customer_phone CHECK (phone REGEXP "^(\\+966|0)?[5][0-9]{8}$")');
        DB::statement('ALTER TABLE potential_customers ADD CONSTRAINT chk_potential_customer_email CHECK (email IS NULL OR email REGEXP "^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$")');
    }

    /**
     * إضافة قيود التحقق لجدول الاشتراكات المعلقة
     */
    private function addPendingSubscriptionConstraints(): void
    {
        DB::statement('ALTER TABLE pending_subscriptions ADD CONSTRAINT chk_pending_subscription_phone CHECK (phone REGEXP "^(\\+966|0)?[5][0-9]{8}$")');
        DB::statement('ALTER TABLE pending_subscriptions ADD CONSTRAINT chk_pending_subscription_id_number CHECK (id_number REGEXP "^[12][0-9]{9}$")');
        DB::statement('ALTER TABLE pending_subscriptions ADD CONSTRAINT chk_pending_subscription_email CHECK (email IS NULL OR email REGEXP "^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$")');
        DB::statement('ALTER TABLE pending_subscriptions ADD CONSTRAINT chk_pending_subscription_total_amount CHECK (total_amount > 0)');
        DB::statement('ALTER TABLE pending_subscriptions ADD CONSTRAINT chk_pending_subscription_dependents_count CHECK (dependents_count >= 0 AND dependents_count <= 20)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إزالة قيود التحقق من جدول المراكز الطبية
        DB::statement('ALTER TABLE medical_centers DROP CONSTRAINT IF EXISTS chk_medical_center_type');
        DB::statement('ALTER TABLE medical_centers DROP CONSTRAINT IF EXISTS chk_medical_center_rating');
        DB::statement('ALTER TABLE medical_centers DROP CONSTRAINT IF EXISTS chk_medical_center_reviews_count');
        DB::statement('ALTER TABLE medical_centers DROP CONSTRAINT IF EXISTS chk_medical_center_views_count');
        DB::statement('ALTER TABLE medical_centers DROP CONSTRAINT IF EXISTS chk_medical_center_latitude');
        DB::statement('ALTER TABLE medical_centers DROP CONSTRAINT IF EXISTS chk_medical_center_longitude');
        DB::statement('ALTER TABLE medical_centers DROP CONSTRAINT IF EXISTS chk_medical_center_phone');
        DB::statement('ALTER TABLE medical_centers DROP CONSTRAINT IF EXISTS chk_medical_center_email');
        DB::statement('ALTER TABLE medical_centers DROP CONSTRAINT IF EXISTS chk_medical_center_contract_dates');

        // إزالة قيود التحقق من جدول المشتركين
        DB::statement('ALTER TABLE subscribers DROP CONSTRAINT IF EXISTS chk_subscriber_phone');
        DB::statement('ALTER TABLE subscribers DROP CONSTRAINT IF EXISTS chk_subscriber_id_number');
        DB::statement('ALTER TABLE subscribers DROP CONSTRAINT IF EXISTS chk_subscriber_email');
        DB::statement('ALTER TABLE subscribers DROP CONSTRAINT IF EXISTS chk_subscriber_dates');
        DB::statement('ALTER TABLE subscribers DROP CONSTRAINT IF EXISTS chk_subscriber_card_price');
        DB::statement('ALTER TABLE subscribers DROP CONSTRAINT IF EXISTS chk_subscriber_total_amount');
        DB::statement('ALTER TABLE subscribers DROP CONSTRAINT IF EXISTS chk_subscriber_dependents_count');
        DB::statement('ALTER TABLE subscribers DROP CONSTRAINT IF EXISTS chk_subscriber_discount_percentage');
        DB::statement('ALTER TABLE subscribers DROP CONSTRAINT IF EXISTS chk_subscriber_discount_amount');

        // إزالة قيود التحقق من جدول العروض
        DB::statement('ALTER TABLE offers DROP CONSTRAINT IF EXISTS chk_offer_discount_percentage');
        DB::statement('ALTER TABLE offers DROP CONSTRAINT IF EXISTS chk_offer_discount_amount');
        DB::statement('ALTER TABLE offers DROP CONSTRAINT IF EXISTS chk_offer_original_price');
        DB::statement('ALTER TABLE offers DROP CONSTRAINT IF EXISTS chk_offer_discounted_price');
        DB::statement('ALTER TABLE offers DROP CONSTRAINT IF EXISTS chk_offer_dates');
        DB::statement('ALTER TABLE offers DROP CONSTRAINT IF EXISTS chk_offer_max_uses');
        DB::statement('ALTER TABLE offers DROP CONSTRAINT IF EXISTS chk_offer_current_uses');
        DB::statement('ALTER TABLE offers DROP CONSTRAINT IF EXISTS chk_offer_uses_limit');

        // إزالة قيود التحقق من جدول المدفوعات
        DB::statement('ALTER TABLE payments DROP CONSTRAINT IF EXISTS chk_payment_amount');
        DB::statement('ALTER TABLE payments DROP CONSTRAINT IF EXISTS chk_payment_transfer_amount');
        DB::statement('ALTER TABLE payments DROP CONSTRAINT IF EXISTS chk_payment_verification_dates');

        // إزالة قيود التحقق من جدول الباقات
        DB::statement('ALTER TABLE packages DROP CONSTRAINT IF EXISTS chk_package_price');
        DB::statement('ALTER TABLE packages DROP CONSTRAINT IF EXISTS chk_package_dependent_price');
        DB::statement('ALTER TABLE packages DROP CONSTRAINT IF EXISTS chk_package_duration_months');
        DB::statement('ALTER TABLE packages DROP CONSTRAINT IF EXISTS chk_package_max_dependents');
        DB::statement('ALTER TABLE packages DROP CONSTRAINT IF EXISTS chk_package_discount_percentage');

        // إزالة قيود التحقق من جدول التابعين
        DB::statement('ALTER TABLE dependents DROP CONSTRAINT IF EXISTS chk_dependent_id_number');
        DB::statement('ALTER TABLE dependents DROP CONSTRAINT IF EXISTS chk_dependent_price');

        // إزالة قيود التحقق من جدول العملاء المحتملين
        DB::statement('ALTER TABLE potential_customers DROP CONSTRAINT IF EXISTS chk_potential_customer_phone');
        DB::statement('ALTER TABLE potential_customers DROP CONSTRAINT IF EXISTS chk_potential_customer_email');

        // إزالة قيود التحقق من جدول الاشتراكات المعلقة
        DB::statement('ALTER TABLE pending_subscriptions DROP CONSTRAINT IF EXISTS chk_pending_subscription_phone');
        DB::statement('ALTER TABLE pending_subscriptions DROP CONSTRAINT IF EXISTS chk_pending_subscription_id_number');
        DB::statement('ALTER TABLE pending_subscriptions DROP CONSTRAINT IF EXISTS chk_pending_subscription_email');
        DB::statement('ALTER TABLE pending_subscriptions DROP CONSTRAINT IF EXISTS chk_pending_subscription_total_amount');
        DB::statement('ALTER TABLE pending_subscriptions DROP CONSTRAINT IF EXISTS chk_pending_subscription_dependents_count');
    }
};
