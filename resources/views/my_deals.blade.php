@extends('layouts.app')

@section('title', 'Forum')

@section('content')
    <main id="tt-pageContent" class="tt-offset-small" style="min-height: 80vh">
        <div class="tt-wrapper-section">
            <div class="container">
                <div class="tt-user-header">
                    <div class="tt-col-avatar">
                        <div class="tt-icon">
                            @if (Auth::user()->profile_photo_path)
                                <img src="{{ asset(Auth::user()->profile_photo_path) }}" alt="">
                            @else
                                <img src="{{ asset('assets/images/user.png') }}" alt="">
                            @endif
                        </div>
                    </div>
                    <div class="tt-col-title">
                        <div class="tt-title">
                            <a href="javascript::void(0)">{{ Auth::user()->name }}</a>
                        </div>
                        <ul class="tt-list-badge">
                            <li><a href="javascript::void(0)"><span class="tt-color14 tt-badge">LVL : 26</span></a></li>
                        </ul>
                    </div>
                    <div class="tt-col-btn" id="js-settings-btn">
                        <div class="tt-list-btn">
                            <a href="#" class="tt-btn-icon">
                                <svg class="tt-icon">
                                    <use xlink:href="#icon-settings_fill"></use>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mb-5">
            <div class="tt-tab-wrapper">
                <div class="tab-content">
                    <div class="tab-pane tt-indent-none  show active" id="tt-tab-01" role="tabpanel">
                        <div class="tt-topic-list">
                            <div class="tt-list-header">
                                <div class="tt-col-topic">Topic</div>
                                <div class="tt-col-value-large hide-mobile">Up Voted</div>
                                <div class="tt-col-value-large hide-mobile">Down Voted</div>
                                <div class="tt-col-value-large hide-mobile">Comments</div>
                                <div class="tt-col-value-large hide-mobile">Status</div>
                                <div class="tt-col-value-large hide-mobile">Action</div>
                            </div>
                            @foreach ($posts as $post)
                                <div class="tt-item">
                                    <div class="tt-col-description">
                                        <h6 class="tt-title"><a href="{{ route('view-deal', $post->id) }}">
                                                {{ $post->title }}
                                            </a></h6>
                                        <div class="tt-col-message">
                                            {{ $post->description }}
                                        </div>
                                        {{-- Mobile --}}
                                        <div class="row align-items-center no-gutters hide-desktope">
                                            <div class="col-9">
                                                <ul class="tt-list-badge">
                                                    <li class="show-mobile"><a href="#"><span
                                                                class="tt-color05 tt-badge">music</span></a></li>
                                                </ul>
                                                <a href="#" class="tt-btn-icon show-mobile">
                                                    <i class="tt-icon"><svg>
                                                            <use xlink:href="#icon-reply"></use>
                                                        </svg></i>
                                                </a>
                                            </div>
                                            <div class="col-3 ml-auto show-mobile">
                                                <div class="tt-value">5 Jan,19</div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="tt-col-value-large hide-mobile">{{ $post->upvotes }}</div>
                                    <div class="tt-col-value-large hide-mobile">{{ $post->downvotes }}</div>
                                    <div class="tt-col-value-large hide-mobile">{{ $post->comment_count }}</div>


                                    <div class="tt-col-value-large hide-mobile">
                                        @if ($post->status == 1)
                                            <span class="badge bg-success px-3 py-2">Active</span>
                                        @elseif ($post->status == 2)
                                            <span class="badge bg-danger px-3 py-2">Rejected</span>
                                        @else
                                            <span class="badge bg-secondary px-3 py-2">Inactive</span>
                                        @endif
                                    </div>

                                    <div class="tt-col-value-large hide-mobile"> <!-- Edit Button -->
                                        <a href="{{ route('edit-deals', $post->id) }}"
                                            class="btn btn-sm btn-primary mb-2">
                                            Edit
                                        </a>

                                        <!-- Helpful Toggle Button -->
                                        @if ($post->helpful_by_user)
                                            {{-- Assuming this is a boolean indicating current user marked helpful --}}
                                            <form method="POST" action="{{ route('deals.unmarkHelpful', $post->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-warning">
                                                    Remove Helpful
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('deals.markHelpful', $post->id) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    Mark Helpful
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                </div>
                            @endforeach
                            <br>
                            <!-- Pagination links -->
                            {{ $posts->links() }}

                        </div>
                    </div>
                </div>
            </div>
        </div>
</main>@endsection
