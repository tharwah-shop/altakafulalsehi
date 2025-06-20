<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'file_name',
        'file_path',
        'file_type',
        'mime_type',
        'file_size',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    /**
     * العلاقات
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Scopes
     */
    public function scopeImages($query)
    {
        return $query->where('file_type', 'image');
    }

    public function scopeDocuments($query)
    {
        return $query->where('file_type', 'document');
    }

    public function scopeVideos($query)
    {
        return $query->where('file_type', 'video');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Accessors
     */
    public function getFileSizeHumanAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getFullUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
}
