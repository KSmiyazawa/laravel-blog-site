<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;


class PostController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(): Response
    {
        $posts = Post::with('user')
            ->latest()
            ->get();

        return Inertia::render('posts/list', [
            'posts' => $posts,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('posts/create');
    }

    protected function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = 1;
        $originalSlug = $slug;

        while (Post::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    public function store(PostRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['slug'] = $this->generateUniqueSlug($data['title']);
        
        $post = Post::create($data);

        if ($request->hasFile('featured_image')) {
            $post->featured_image = $request->file('featured_image')->store('posts', 'public');
            $post->save();
        }

        return redirect()->route('posts.show', ['post' => $post->slug]);
    }

    public function show(Post $post): Response
    {
        $canEdit = auth()->check() && auth()->id() === $post->user_id;
        return Inertia::render('posts/show', [
            'post' => $post->load('user'),
            'canEdit' => $canEdit,
        ]);
    }

    public function edit(Post $post): Response
    {
        $this->authorize('update', $post);
        return Inertia::render('posts/edit', [
            'post' => $post->load('user'),
        ]);
    }

    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        $this->authorize('update', $post);
        
        $data = $request->validated();
        
        if ($post->title !== $data['title']) {
            $data['slug'] = $this->generateUniqueSlug($data['title']);
        }

        // Remove featured_image from data if no new image was uploaded
        if (!isset($data['featured_image'])) {
            unset($data['featured_image']);
        }

        $post->update($data);

        if ($request->hasFile('featured_image')) {
            // Delete the old image if it exists
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            
            $post->featured_image = $request->file('featured_image')->store('posts', 'public');
            $post->save();
        }

        return redirect()->route('posts.show', ['post' => $post->slug]);
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->authorize('delete', $post);
        $post->delete();
        return redirect()->route('posts.index');
    }
}
