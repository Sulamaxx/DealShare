@extends('backend.layout.layout')

@section('title', $post->title)

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">{{ $post->title }}</h3>
                <small class="text-light">Posted by {{ $post->user->name }} on
                    {{ $post->created_at->format('F j, Y') }}</small>
            </div>

            <div class="card-body">
                {{-- Post Image --}}
                @if ($post->image)
                    <img src="{{ asset('storage/deals/' . $post->image) }}" class="img-fluid rounded mb-3"
                        alt="{{ $post->title }}">
                @endif

                {{-- Post Description --}}
                <p>{!! nl2br(e($post->description)) !!}</p>

                {{-- Post Link --}}
                @if ($post->link)
                    <a href="{{ $post->link }}" class="btn btn-outline-primary mt-3" target="_blank">
                        View Deal
                    </a>
                @endif

                {{-- Discount Text --}}
                @if ($post->discount_text)
                    <p><strong>Discount:</strong> {{ $post->discount_text }}</p>
                @endif

                {{-- Price Saving --}}
                @if ($post->price_saving)
                    <p><strong>Price Saving:</strong> {{ $post->price_saving }}</p>
                @endif
            </div>

            <div class="card-footer text-muted ms-4 mb-4">
                <div class="row d-flex justify-content-between flex-row">
                    <div class="col-auto">
                        <strong>Category:</strong> {{ $post->category ?? 'Uncategorized' }}
                    </div>
                    <div class="col-auto">
                        <strong>Votes:</strong> {{ $post->upvotes - $post->downvotes }}
                    </div>
                    <div class="col-auto">
                        <strong>Comments:</strong> {{ $post->comment_count }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Comments Section --}}
        <div class="mt-5">
            <h4>Comments</h4>
            @if ($post->comments == null)
                <p>No comments yet. Be the first to comment!</p>
            @else
                @forelse ($post->comments as $comment)
                    @include('backend.deals.comment', ['comment' => $comment])
                @empty
                    <p>No comments yet. Be the first to comment!</p>
                @endforelse
            @endif
        </div>
    </div>
@endsection
