<x-mail::message>
    {{ $bodyContent }}

    @if ($buttonUrl && $buttonText)
        <x-mail::button :url="$buttonUrl">
            {{ $buttonText }}
        </x-mail::button>
        Thanks,<br>
        {{ config('app.name') }}
    @endif

</x-mail::message>
