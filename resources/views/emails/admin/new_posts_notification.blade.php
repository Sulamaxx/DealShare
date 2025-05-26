<x-mail::message>
# New Posts Milestone Reached

Hello Admin,

This is an automated notification from **{{ config('app.name') }}**.

We're excited to inform you that we've reached a new milestone! There are now **{{ $postsCount }} new posts** since the last notification was sent, exceeding the threshold of {{ $threshold }}.

Keep up the great work!

<x-mail::button :url="url('/admin/deals/deals-list')">
View All Posts
</x-mail::button>

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>
