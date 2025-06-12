<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear any existing images in the posts directory
        Storage::disk('public')->deleteDirectory('posts');
        
        // Create a few users to have posts from different authors
        $users = User::factory()->count(3)->create();

        // Create 15 posts with varied content
        Post::factory()
            ->count(15)
            ->sequence(
                // Mix of posts with and without featured images
                ['featured_image' => null],
                fn () => Post::factory()->withFeaturedImage()->make()->getAttributes(),
            )
            ->sequence(function () use ($users) {
                // Randomly assign posts to different users
                return ['user_id' => $users->random()->id];
            })
            ->create();

        // Create some additional posts with specific states
        Post::factory()
            ->count(5)
            ->withFeaturedImage()
            ->create([
                'user_id' => $users->first()->id,
                'content' => fake()->paragraphs(5, true), // Longer content
            ]);
    }
} 