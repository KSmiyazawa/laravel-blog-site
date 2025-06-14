<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'content',
        'featured_image',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate a unique slug for a post.
     */
    public static function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title); // Convert title to slug (e.g., "My Post" → "my-post")
        $count = 1;
        $original = $slug;

        // Check if the slug already exists in the database
        while (static::where('slug', $slug)->exists()) {
            // If it does, append a number to make it unique (e.g., "my-post-1")
            $slug = $original . '-' . $count++;
            $count++;
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug'; // This allows route model binding by slug
    }
}
