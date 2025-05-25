<x-mail::message>
{{-- The content passed from the Mailable will be rendered here --}}
{!! $bodyContent !!}

<x-mail::button :url="config('app.url')">
Visit Our Website
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
