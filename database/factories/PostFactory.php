<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(6);

        return [
            'title' => $title,
            'description' => $this->faker->paragraph(3),
            'link' => $this->faker->url(),
            'upvotes' => $this->faker->numberBetween(0, 100),
            'downvotes' => $this->faker->numberBetween(0, 20),
            // Default date range (can be overridden in the seeder)
            'posted_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'comment_count' => $this->faker->numberBetween(0, 30),
            'category' => $this->faker->randomElement(['Electronics', 'Fashion', 'Home & Kitchen', 'Groceries', 'Other']),
            'verified_member' => $this->faker->boolean(70), // Randomly set verified status on the post
            'image' => 'https://via.placeholder.com/640x480.png/' . $this->faker->hexcolor() . '?text=' . urlencode(Str::limit($title, 15)), // Placeholder image
            'discount_text' => $this->faker->randomElement(['50% Off', 'Flat ₹500 Off', 'Buy 1 Get 1', 'Limited Time Offer', 'Extra 10% Cashback']),
            'price_saving' => $this->faker->randomFloat(2, 50, 2000),
            'post_by' => User::factory(), // This will create a new user for each post by default
            'helpful_by_user' => $this->faker->numberBetween(0, 50), // Assuming this is a count
            'reported_count' => $this->faker->numberBetween(0, 10),
            'status' => $this->faker->randomElement([0, 1, 1, 1]), // Higher chance of status 1 (published)
        ];
    }

    public function published(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 1,
                'posted_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            ];
        });
    }
}
