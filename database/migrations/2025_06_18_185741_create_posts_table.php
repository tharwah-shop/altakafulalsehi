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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title_ar');
            $table->string('title_en')->nullable();
            $table->string('slug')->unique();
            $table->text('content_ar');
            $table->text('content_en')->nullable();
            $table->text('excerpt_ar')->nullable();
            $table->text('excerpt_en')->nullable();
            $table->string('featured_image')->nullable();
            $table->foreignId('category_id')->constrained('post_categories')->onDelete('cascade');
            $table->foreignId('medical_center_id')->constrained()->onDelete('cascade');
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['draft', 'published', 'archived', 'pending'])->default('draft');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->boolean('is_featured')->default(false);
            $table->boolean('allow_comments')->default(true);
            $table->json('tags')->nullable(); // تاجات المنشور
            $table->json('meta_data')->nullable(); // بيانات إضافية
            $table->timestamp('published_at')->nullable();
            $table->integer('views_count')->default(0);
            $table->integer('likes_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
