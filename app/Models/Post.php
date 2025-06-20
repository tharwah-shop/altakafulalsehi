<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ar',
        'title_en',
        'slug',
        'content_ar',
        'content_en',
        'excerpt_ar',
        'excerpt_en',
        'featured_image',
        'category_id',
        'medical_center_id',
        'author_id',
        'status',
        'priority',
        'is_featured',
        'allow_comments',
        'tags',
        'meta_data',
        'published_at',
        'views_count',
        'likes_count',
        'comments_count',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'meta_data' => 'array',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
            'published_at' => 'datetime',
            'views_count' => 'integer',
            'likes_count' => 'integer',
            'comments_count' => 'integer',
        ];
    }

    /**
     * العلاقات
     */
    public function category()
    {
        return $this->belongsTo(PostCategory::class);
    }

    public function medicalCenter()
    {
        return $this->belongsTo(MedicalCenter::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function attachments()
    {
        return $this->hasMany(PostAttachment::class);
    }

    /**
     * Scopes
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByMedicalCenter($query, $centerId)
    {
        return $query->where('medical_center_id', $centerId);
    }

    /**
     * Mutators
     */
    public function setTitleArAttribute($value)
    {
        $this->attributes['title_ar'] = $value;
        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    /**
     * Accessors
     */
    public function getTitleAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->title_ar : $this->title_en;
    }

    public function getContentAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->content_ar : $this->content_en;
    }

    public function getExcerptAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->excerpt_ar : $this->excerpt_en;
    }

    /**
     * Helper Methods
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function incrementLikes()
    {
        $this->increment('likes_count');
    }

    public function isPublished()
    {
        return $this->status === 'published' && $this->published_at <= now();
    }
}
