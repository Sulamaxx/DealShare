<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Str;

class PostObserver
{
    public function creating(Post $post): void
    {
        $this->generateUniqueSlug($post);
    }

    /**
     * Handle the Post "updating" event.
     */
    public function updating(Post $post): void
    {
        // Only regenerate slug if the title has changed or if slug was previously null/empty
        if ($post->isDirty('title') || empty($post->slug)) {
            $this->generateUniqueSlug($post);
        }
    }

    /**
     * Generates a unique slug for the given post.
     */
    protected function generateUniqueSlug(Post $post): void
    {
        $baseSlug = Str::slug($post->title);
        $slug = $baseSlug;
        $counter = 1;

        // Loop until a unique slug is found
        while (Post::where('slug', $slug)
            ->where('id', '!=', $post->id) // Exclude current post if it's an update
            ->exists()
        ) {
            $counter++;
            $slug = $baseSlug . '-' . $counter;
        }

        $post->slug = $slug;
    }
}
