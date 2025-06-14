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
        // Only allow logged-in users to access create/edit/delete routes
        // Public can only view the post list and post detail pages
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Show a list of all posts.
     */
    public function index(): Response
    {
        // Get all posts sorted by latest
        $posts = Post::with('user')
            ->latest()
            ->get();

        return Inertia::render('posts/list', [
            'posts' => $posts,
        ]);
    }

    /**
     * Show the form for creating a new post.
     */
    public function create(): Response
    {
        return Inertia::render('posts/create');
    }

    /**
     * Store a new post.
     */
    public function store(PostRequest $request): RedirectResponse
    {
        // Validate the request data
        $data = $request->validated();

        // Add the logged-in user's ID and generate a unique slug
        $data['user_id'] = auth()->id();
        $data['slug'] = Post::generateUniqueSlug($data['title']);

        // Create the post
        $post = Post::create($data);

        // Handle featured image upload
        $this->handleImageUpload($request, $post);

        // Redirect to the post detail page
        return redirect()->route('posts.show', ['post' => $post->slug]);
    }

    /**
     * Show a single post.
     */
    public function show(Post $post): Response
    {
        // Check if the current user is the owner of the post
        $canEdit = auth()->check() && auth()->id() === $post->user_id;

        return Inertia::render('posts/show', [
            'post' => $post->load('user'),
            'canEdit' => $canEdit, // Send permission info to frontend
        ]);
    }

    /**
     * Show the form for editing an existing post.
     */
    public function edit(Post $post): Response
    {
        // Ensure only the post owner can access this page
        $this->authorize('update', $post);
        return Inertia::render('posts/edit', [
            'post' => $post->load('user'),
        ]);
    }

    /**
     * Update an existing post.
     */
    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        // Ensure only the post owner can update it
        $this->authorize('update', $post);

        // Validate the request data
        $data = $request->validated();

        // If the title has changed, generate a new unique slug
        if ($post->title !== $data['title']) {
            $data['slug'] = Post::generateUniqueSlug($data['title']);
        }

        $post->update($data);

        // Handle featured image upload
        $this->handleImageUpload($request, $post);

        // Redirect to the post detail page
        return redirect()->route('posts.show', ['post' => $post->slug]);
    }

    /**
     * Delete a post.
     */
    public function destroy(Post $post): RedirectResponse
    {
        // Ensure only the post owner can delete it
        $this->authorize('delete', $post);

        $post->delete();

        // Redirect to the post list page
        return redirect()->route('posts.index');
    }

    /**
     * Handle featured image upload and replacement.
     */
    private function handleImageUpload(PostRequest $request, Post $post): void
    {
        if ($request->hasFile('featured_image')) {
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }

            $path = $request->file('featured_image')->store('posts', 'public');
            $post->update(['featured_image' => $path]);
        }
    }
}
