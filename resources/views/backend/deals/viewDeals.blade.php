@extends('backend.layout.layout')

@section('title', $deal->title)

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">{{ $deal->title }}</h3>
                <small class="text-light">Posted by {{ $deal->user->name }} on
                    {{ $deal->created_at->format('F j, Y') }}</small>
            </div>

            <div class="card-body">
                @if ($deal->image)
                    <img src="{{ asset('storage/deals/' . $deal->image) }}" class="img-fluid rounded mb-3"
                        alt="{{ $deal->title }}">
                @endif

                <p>{!! nl2br(e($deal->description)) !!}</p>

                @if ($deal->link)
                    <a href="{{ $deal->link }}" class="btn btn-outline-primary mt-3" target="_blank">
                        View Deal
                    </a>
                @endif
            </div>

            <div class="card-footer text-muted">
                <div class="d-flex justify-content-between">
                    <div>
                        <strong>Category:</strong> {{ $deal->category->name ?? 'Uncategorized' }}
                    </div>
                    <div>
                        <strong>Votes:</strong> {{ $deal->votes_count ?? 0 }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Comments Section --}}
        <div class="mt-5">
            <h4>Comments</h4>
            @foreach ($deal->comments as $comment)
                <div class="card my-2">
                    <div class="card-body">
                        <strong>{{ $comment->user->name }}</strong> <small
                            class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                        <p>{{ $comment->body }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
