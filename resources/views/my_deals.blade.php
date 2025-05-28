@extends('layouts.app')

@section('title', 'My Deals')

@section('content')
    <main id="tt-pageContent" class="tt-offset-small" style="min-height: 80vh">
        <div class="tt-wrapper-section">
            <div class="container">
                <div class="tt-user-header">
                    <div class="tt-col-avatar tt-avatar-with-badge" style="width: fit-content;"> {{-- Added tt-avatar-with-badge class --}}
                        <div class="tt-icon">
                            @php
                                $user = Auth::user(); // Get authenticated user once
                                $profilePhotoUrl =
                                    $user && $user->profile_photo_path
                                        ? asset('storage/' . $user->profile_photo_path)
                                        : asset('assets/images/user.png');
                            @endphp
                            <img src="{{ $profilePhotoUrl }}" alt="{{ $user->name ?? 'User' }}'s avatar"
                                style="width:40px;height:40px;border-radius:30px; object-fit: cover;">
                        </div>

                        {{-- --- Badge positioned absolutely within tt-col-avatar --- --}}
                        <ul class="tt-list-badge tt-badge-overlay" style="margin-left: 0px;">

                            @if ($badge) {{-- Check if a badge was found --}}
                                <li>
                                    <a href="#" title="{{ $badge->description }}">
                                        <span class="tt-color-default tt-badge" style="padding-left: 0px;">
                                            @if ($badge->icon)
                                                {{-- Assuming badge->icon is the image path --}}
                                                <img src="{{ asset($badge->icon) }}" alt="{{ $badge->name }}"
                                                    style="max-width: 22.5px; height: auto; vertical-align: middle; margin-right: 5px;">
                                            @else
                                                {{-- Fallback if no image, maybe use name or text --}}
                                                {{ $badge->name }}
                                            @endif
                                            {{-- ({{ $badge->vote_count }}) --}}
                                        </span>
                                    </a>
                                </li>
                            @else
                                {{-- Show this if no qualifying badge was found --}}
                                <li><span class="tt-color-none tt-badge" style="color:black">No Badges Earned Yet</span>
                                </li>
                            @endif
                        </ul>
                        {{-- --------------------------------------------------- --}}

                    </div>
                    <div class="tt-col-title" style="padding-left: 0px;">
                        <div class="tt-title">
                            <a href="javascript::void(0)">{{ Auth::user()->name }}</a>
                        </div>
                        <ul class="tt-list-badge" style="margin-left: 0px;">

                            @if ($badge)
                                {{-- Check if a badge was found --}}
                                <li>
                                    <a href="#" title="{{ $badge->description }}">
                                        <span class="tt-color-default tt-badge"
                                            style="padding-left: 0px;color: #666;margin-left: 10px;">

                                            {{ $badge->name }}

                                        </span>
                                    </a>
                                </li>
                            @else
                                {{-- Show this if no qualifying badge was found --}}
                                <li><span class="tt-color-none tt-badge" style="color:black">No Badges Earned Yet</span>
                                </li>
                            @endif
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
                                <div class="tt-col-value-large hide-mobile" style="white-space: nowrap;">Down Voted</div>
                                <div class="tt-col-value-large hide-mobile">Comments</div>
                                <div class="tt-col-value-large hide-mobile">Status</div>
                                <div class="tt-col-value-large hide-mobile">Action</div>
                            </div>
                            @foreach ($posts as $post)
                                <div class="tt-item">
                                    <div class="tt-col-description">
                                        <h6 class="tt-title"><a
                                                href="{{ route('view-deal-title', ['post' => $deal->slug]) }}">
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
                                        <a href="{{ route('edit-deals', $post->id) }}" class="btn btn-sm btn-primary mb-2">
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
    </main>

    <style>
        /* Ensure the avatar container is the positioning context */
        .tt-col-avatar.tt-avatar-with-badge {
            position: relative;
            /* Ensure it has a defined width/height if not already set by its content */
            /* Example: width: 40px; height: 40px; if the image doesn't define it */
        }

        /* Style for the badge when it's an overlay */
        .tt-list-badge.tt-badge-overlay {
            position: absolute;
            bottom: -11px;
            /* Adjust as needed to pull it down */
            right: -2.5px;
            /* Adjust as needed to pull it right */
            z-index: 10;
            /* Ensure it's above the avatar */
            margin: 0;
            /* Remove any default list margins */
            padding: 0;
            /* Remove any default list padding */
            list-style: none;
            /* Remove bullet points */
            /* Optional: Use transform to fine-tune positioning, especially for rounded elements */
            /* transform: translate(25%, 25%); */
        }

        /* Style for the individual badge within the overlay */
        .tt-badge.tt-badge-small {
            display: flex;
            /* Use flex to align icon and text inside badge */
            align-items: center;
            justify-content: center;
            /* Center content if small */
            padding: 2px 4px;
            /* Smaller padding for a compact badge */
            font-size: 0.7rem;
            /* Smaller font size */
            border-radius: 8px;
            /* More rounded corners for a badge */
            white-space: nowrap;
            /* Prevent text wrapping */
            min-width: 20px;
            /* Ensure a minimum size */
            min-height: 20px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            /* Optional: add a subtle shadow */
        }

        /* Ensure the badge icon is correctly sized within the small badge */
        .tt-badge.tt-badge-small img {
            max-width: 14px !important;
            /* Make icon even smaller */
            height: auto;
            margin-right: 2px;
            /* Smaller margin for compact layout */
        }

        /* If no badge earned, hide the empty span */
        .tt-badge-overlay .tt-color-none.tt-badge {
            display: none;
        }
    </style>

@endsection
