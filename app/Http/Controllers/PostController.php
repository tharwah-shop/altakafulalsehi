<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\MedicalCenter;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Post::published()->with(['category', 'medicalCenter', 'author']);

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title_ar', 'like', "%{$search}%")
                  ->orWhere('title_en', 'like', "%{$search}%")
                  ->orWhere('content_ar', 'like', "%{$search}%")
                  ->orWhere('content_en', 'like', "%{$search}%");
            });
        }

        // فلترة حسب التصنيف
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // فلترة حسب المركز الطبي
        if ($request->filled('medical_center')) {
            $query->where('medical_center_id', $request->medical_center);
        }

        $posts = $query->latest('published_at')->paginate(12);
        $categories = PostCategory::active()->ordered()->get();
        $medicalCenters = MedicalCenter::active()->select('id', 'name')->get();

        return view('posts.index', compact('posts', 'categories', 'medicalCenters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = PostCategory::active()->ordered()->get();
        $medicalCenters = MedicalCenter::active()->select('id', 'name')->get();

        return view('admin.posts.create', compact('categories', 'medicalCenters'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'content_ar' => 'required|string',
            'content_en' => 'nullable|string',
            'excerpt_ar' => 'nullable|string|max:500',
            'excerpt_en' => 'nullable|string|max:500',
            'category_id' => 'required|exists:post_categories,id',
            'medical_center_id' => 'required|exists:medical_centers,id',
            'status' => 'required|in:draft,published,archived,pending',
            'priority' => 'required|in:low,normal,high,urgent',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
            'tags' => 'nullable|array',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'published_at' => 'nullable|date',
        ]);

        // رفع الصورة المميزة
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }

        // إضافة معرف الكاتب
        $validated['author_id'] = auth()->id();

        $post = Post::create($validated);

        return redirect()->route('posts.show', $post->slug)
                        ->with('success', 'تم إنشاء المنشور بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $post = Post::where('slug', $slug)
                   ->published()
                   ->with(['category', 'medicalCenter', 'author', 'attachments'])
                   ->firstOrFail();

        // زيادة عدد المشاهدات
        $post->incrementViews();

        // منشورات ذات صلة
        $relatedPosts = Post::published()
                           ->where('category_id', $post->category_id)
                           ->where('id', '!=', $post->id)
                           ->take(3)
                           ->get();

        return view('posts.show', compact('post', 'relatedPosts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $categories = PostCategory::active()->ordered()->get();
        $medicalCenters = MedicalCenter::active()->select('id', 'name')->get();

        return view('admin.posts.edit', compact('post', 'categories', 'medicalCenters'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'content_ar' => 'required|string',
            'content_en' => 'nullable|string',
            'excerpt_ar' => 'nullable|string|max:500',
            'excerpt_en' => 'nullable|string|max:500',
            'category_id' => 'required|exists:post_categories,id',
            'medical_center_id' => 'required|exists:medical_centers,id',
            'status' => 'required|in:draft,published,archived,pending',
            'priority' => 'required|in:low,normal,high,urgent',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
            'tags' => 'nullable|array',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'published_at' => 'nullable|date',
        ]);

        // رفع الصورة المميزة الجديدة
        if ($request->hasFile('featured_image')) {
            // حذف الصورة القديمة
            if ($post->featured_image) {
                \Storage::disk('public')->delete($post->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }

        $post->update($validated);

        return redirect()->route('posts.show', $post->slug)
                        ->with('success', 'تم تحديث المنشور بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // حذف الصورة المميزة
        if ($post->featured_image) {
            \Storage::disk('public')->delete($post->featured_image);
        }

        // حذف المرفقات
        foreach ($post->attachments as $attachment) {
            \Storage::disk('public')->delete($attachment->file_path);
        }

        $post->delete();

        return redirect()->route('admin.posts.index')
                        ->with('success', 'تم حذف المنشور بنجاح');
    }
}
