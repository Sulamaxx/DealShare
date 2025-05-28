<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class PopulatePostSlugs extends Command
{
    protected $signature = 'posts:populate-slugs';
    protected $description = 'Populates the slug column for existing posts.';

    public function handle()
    {
        // Get posts that are missing a slug OR where the slug is empty (to re-attempt failed ones)
        $posts = Post::whereNull('slug')->orWhere('slug', '')->get();
        $this->info("Found " . $posts->count() . " posts without slugs.");

        foreach ($posts as $post) {
            if (empty($post->title)) {
                $this->warn("Skipping post ID: {$post->id} due to missing title.");
                continue; // Skip to the next post
            }

            $baseSlug = Str::slug($post->title);
            $slug = $baseSlug;
            $counter = 1;

            // Loop until a unique slug is found
            while (Post::where('slug', $slug)
                ->where('id', '!=', $post->id) // Exclude current post if it already has a slug and we're just re-validating
                ->exists()
            ) {
                $counter++;
                $slug = $baseSlug . '-' . $counter;
            }

            // Only update if the slug has actually changed (e.g., if it was null or a duplicate)
            if ($post->slug !== $slug) {
                $post->slug = $slug;
                try {
                    $post->save();
                    $this->line("Populated slug for post ID: {$post->id} - '{$post->slug}'");
                } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                    $this->error("Failed to populate slug for post ID: {$post->id} due to unique constraint: " . $e->getMessage());
                    // This catch block is mostly for safety; the while loop *should* prevent this.
                } catch (\Exception $e) {
                    $this->error("An error occurred for post ID: {$post->id}: " . $e->getMessage());
                }
            } else {
                $this->line("Post ID: {$post->id} already has a unique slug: '{$post->slug}'");
            }
        }

        $this->info("Slug population complete.");
        return Command::SUCCESS;
    }
}
