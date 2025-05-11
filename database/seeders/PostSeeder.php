<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = Faker::create();

        // Ensure at least 10 users exist. If not, create them.
        if (User::count() < 10) {
             User::factory()->count(10 - User::count())->create();
        }
        // Get IDs of existing users to assign posts to
        $userIds = User::pluck('id')->toArray();

        if (empty($userIds)) {
             // This should ideally not happen if the above runs, but as a safeguard
             echo "No users found to assign posts to. Please run UserSeeder first or ensure User factory creates users.\n";
             return;
        }


        // --- Batch 1: Standard Random Posts (mostly recent to few months old) ---
        echo "Seeding standard random posts...\n";
        Post::factory()->count(80)->create([
            'post_by' => $faker->randomElement($userIds), // Assign a random user ID
            'status' => 1, // Ensure published status for display
            'upvotes' => $faker->numberBetween(0, 100),
            'downvotes' => $faker->numberBetween(0, 20),
            'comment_count' => $faker->numberBetween(0, 30),
            'posted_at' => $faker->dateTimeBetween('-6 months', 'now'), // Posts from last 6 months
            // verified_member will use factory default or can be overridden here
        ]);

        // --- Batch 2: Newer, potentially hot deals (last week) ---
        echo "Seeding newer, hotter deals...\n";
        Post::factory()->count(15)->create([
             'post_by' => $faker->randomElement($userIds),
             'status' => 1, // Ensure published status
             'upvotes' => $faker->numberBetween(50, 500), // Higher upvotes
             'downvotes' => $faker->numberBetween(0, 50),
             'comment_count' => $faker->numberBetween(10, 100), // Higher comments
             'posted_at' => $faker->dateTimeBetween('-7 days', 'now'), // Posted very recently
             // verified_member can be overridden, e.g., 'verified_member' => $faker->boolean(90),
        ]);

        // --- Batch 3: Older, popular deals (to test time decay vs interaction score) ---
        echo "Seeding older, very popular deals...\n";
         Post::factory()->count(5)->create([
             'post_by' => $faker->randomElement($userIds),
             'status' => 1, // Ensure published status
             'upvotes' => $faker->numberBetween(200, 1000), // Very high upvotes
             'downvotes' => $faker->numberBetween(10, 100),
             'comment_count' => $faker->numberBetween(50, 200), // Very high comments
             'posted_at' => $faker->dateTimeBetween('-1 year', '-1 month'), // Posted between 1 month and 1 year ago
             // verified_member can be overridden
        ]);

         echo "Post seeding complete!\n";
    }
}
