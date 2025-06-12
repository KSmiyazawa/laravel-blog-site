<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

beforeEach(function () {
    Storage::fake('public');
});

test('user can view posts index page', function () {
    $user = User::factory()->create();
    $posts = Post::factory()->count(3)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)
        ->get(route('posts.index'));

    $response->assertInertia(fn ($assert) => $assert
        ->component('posts/list')
        ->has('posts', 3)
        ->where('posts.0.title', $posts[0]->title)
        ->where('posts.1.title', $posts[1]->title)
        ->where('posts.2.title', $posts[2]->title)
    );
});

test('user can view create post page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('posts.create'));

    $response->assertInertia(fn ($assert) => $assert
        ->component('posts/create')
    );
});

test('user can create a post', function () {
    $user = User::factory()->create();
    $file = UploadedFile::fake()->image('post.jpg');

    $postData = [
        'title' => 'Test Post',
        'content' => 'This is a test post content.',
        'featured_image' => $file,
    ];

    $response = $this->actingAs($user)
        ->post(route('posts.store'), $postData);

    $post = Post::where('title', 'Test Post')->first();
    $response->assertRedirect(route('posts.show', $post));

    $this->assertDatabaseHas('posts', [
        'user_id' => $user->id,
        'title' => 'Test Post',
        'slug' => Str::slug('Test Post'),
        'content' => 'This is a test post content.',
    ]);

    Storage::disk('public')->assertExists('posts/' . $file->hashName());
});

test('user can view their own post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)
        ->get(route('posts.show', $post));

    $response->assertInertia(fn ($assert) => $assert
        ->component('posts/show')
        ->where('post.title', $post->title)
        ->where('post.content', $post->content)
    );
});

test('user can view edit page for their own post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)
        ->get(route('posts.edit', $post));

    $response->assertInertia(fn ($assert) => $assert
        ->component('posts/edit')
        ->where('post.title', $post->title)
        ->where('post.content', $post->content)
    );
});

test('user cannot view edit page for another users post', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($user)
        ->get(route('posts.edit', $post));

    $response->assertStatus(403);
});

test('user can update their own post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);
    $newFile = UploadedFile::fake()->image('new-post.jpg');

    $updateData = [
        'title' => 'Updated Post Title',
        'content' => 'Updated post content.',
        'featured_image' => $newFile,
    ];

    $response = $this->actingAs($user)
        ->put(route('posts.update', $post), $updateData);

    $updatedPost = Post::find($post->id);
    $response->assertRedirect(route('posts.show', $updatedPost));

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'Updated Post Title',
        'slug' => Str::slug('Updated Post Title'),
        'content' => 'Updated post content.',
    ]);

    Storage::disk('public')->assertExists('posts/' . $newFile->hashName());
});

test('user cannot update another users post', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $otherUser->id]);

    $updateData = [
        'title' => 'Updated Post Title',
        'content' => 'Updated post content.',
    ];

    $response = $this->actingAs($user)
        ->put(route('posts.update', $post), $updateData);

    $response->assertStatus(403);
});

test('user can delete their own post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)
        ->delete(route('posts.destroy', $post));

    $response->assertRedirect(route('posts.index'));

    $this->assertDatabaseMissing('posts', [
        'id' => $post->id,
    ]);
});

test('user cannot delete another users post', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($user)
        ->delete(route('posts.destroy', $post));

    $response->assertStatus(403);
});

test('post creation requires authentication', function () {
    $postData = [
        'title' => 'Test Post',
        'content' => 'This is a test post content.',
    ];

    $response = $this->post(route('posts.store'), $postData);

    $response->assertRedirect(route('login'));
});

test('post update requires authentication', function () {
    $post = Post::factory()->create();

    $updateData = [
        'title' => 'Updated Post Title',
        'content' => 'Updated post content.',
    ];

    $response = $this->put(route('posts.update', $post), $updateData);

    $response->assertRedirect(route('login'));
});

test('post deletion requires authentication', function () {
    $post = Post::factory()->create();

    $response = $this->delete(route('posts.destroy', $post));

    $response->assertRedirect(route('login'));
});

test('unauthenticated user can view posts index page', function () {
    $user = User::factory()->create();
    $posts = Post::factory()->count(3)->create(['user_id' => $user->id]);

    $response = $this->get(route('posts.index'));

    $response->assertInertia(fn ($assert) => $assert
        ->component('posts/list')
        ->has('posts', 3)
        ->where('posts.0.title', $posts[0]->title)
        ->where('posts.1.title', $posts[1]->title)
        ->where('posts.2.title', $posts[2]->title)
    );
});

test('unauthenticated user can view post detail page', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);

    $response = $this->get(route('posts.show', $post));

    $response->assertInertia(fn ($assert) => $assert
        ->component('posts/show')
        ->where('post.title', $post->title)
        ->where('post.content', $post->content)
        ->where('canEdit', false)
    );
}); 