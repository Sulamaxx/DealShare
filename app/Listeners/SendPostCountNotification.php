<?php

namespace App\Listeners;

use App\Events\PostCreated;
use App\Mail\NewPostsThresholdReached;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPostCountNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The name of the cache key for the post count.
     *
     * @var string
     */
    const POST_COUNT_CACHE_KEY = 'new_posts_count_since_notification';

    /**
     * Handle the event.
     *
     * @param  \App\Events\PostCreated  $event
     * @return void
     */
    public function handle(PostCreated $event)
    {
        $threshold = config('mail.posts_notification_threshold', 20); // Get threshold from config
        $adminEmail = config('mail.admin_notification_email'); // Get admin email from config



        if (empty($adminEmail)) {
            Log::warning('ADMIN_NOTIFICATION_EMAIL is not set in .env or config/mail.php. Cannot send post count notification.');
            return; // Stop execution if no admin email is set
        }
        // --- CACHING LOGIC ---
        $initialCacheValue = Cache::get(self::POST_COUNT_CACHE_KEY, 0); // Get current value, default to 0 if not exists

        // Manually increment the count
        $newCount = $initialCacheValue + 1;

        // Store the new count back into the cache
        Cache::put(self::POST_COUNT_CACHE_KEY, $newCount);


        // Get the value *directly from cache* immediately after putting
        $currentCount = Cache::get(self::POST_COUNT_CACHE_KEY);
        // --- END CACHING LOGIC ---

        // Check if the current count is a multiple of the threshold
        if ($currentCount > 0 && ($currentCount % $threshold === 0)) {
            try {
                Mail::to($adminEmail)->send(new NewPostsThresholdReached($currentCount, $threshold));
                Log::info("Admin notification email sent for {$currentCount} new posts.");


                Cache::put(self::POST_COUNT_CACHE_KEY, 0); // Reset to 0


            } catch (\Exception $e) {
                Log::error('Failed to send NewPostsThresholdReached email: ' . $e->getMessage());
            }
        }
    }
}
