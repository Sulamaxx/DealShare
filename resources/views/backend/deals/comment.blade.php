<div class="card my-2">
    <div class="card-body">
        <strong>{{ $comment->user->name }}</strong> <small
            class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
        <p>{{ $comment->body }}</p>

        {{-- Check for Nested Comments --}}
        @if (!empty($comment->comments))
            <div class="ms-4">
                @foreach ($comment->comments as $nestedComment)
                    @include('backend.deals.comment', ['comment' => $nestedComment])
                @endforeach
            </div>
        @endif
    </div>
</div>
