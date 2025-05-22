@extends('layouts.app')

@section('title', 'My Activities')

@section('content')
    {{-- User Header Section (Keep as is) --}}
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
                <div class="tt-col-btn setting-btn-custom" id="js-settings-btn">
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

    {{-- --- Tabs Navigation and Content --- --}}
    <div class="container mb-5 mt-3 post-container-custom">
        <div class="tt-tab-wrapper">

            {{-- --- Tabs Navigation --- --}}
            <ul class="nav nav-tabs" id="activityTabs" role="tablist"> {{-- Assuming Bootstrap-like classes --}}
                <li class="nav-item nav-item-custom" role="presentation">
                    {{-- 'active' class makes this tab initially visible --}}
                    <button class="nav-link active nav-item-button-custom" id="commented-tab" data-bs-toggle="tab"
                        data-bs-target="#commented-pane" type="button" role="tab" aria-controls="commented-pane"
                        aria-selected="true">
                        Commented Deals ({{ $commentedPosts->total() }}) {{-- Display total count --}}
                    </button>
                </li>
                <li class="nav-item nav-item-custom" role="presentation">
                    <button class="nav-link nav-item-button-custom" id="voted-tab" data-bs-toggle="tab"
                        data-bs-target="#voted-pane" type="button" role="tab" aria-controls="voted-pane"
                        aria-selected="false">
                        Voted Deals ({{ $votedPosts->total() }}) {{-- Display total count --}}
                    </button>
                </li>
                <li class="nav-item nav-item-custom" role="presentation">
                    <button class="nav-link nav-item-button-custom" id="subscribed-tab" data-bs-toggle="tab"
                        data-bs-target="#subscribed-pane" type="button" role="tab" aria-controls="subscribed-pane"
                        aria-selected="false">
                        Subscribed Deals ({{ $subscribedPosts->total() }}) {{-- Display total count --}}
                    </button>
                </li>
            </ul>
            {{-- ------------------------- --}}

            {{-- --- Tabs Content Panes --- --}}
            <div class="tab-content" id="activityTabsContent">
                {{-- Commented Deals Pane --}}
                {{-- 'show active' makes this pane initially visible --}}
                <div class="tab-pane fade show active" id="commented-pane" role="tabpanel" aria-labelledby="commented-tab">
                    <div class="tt-topic-list">
                        <div class="tt-list-header">
                            <div class="tt-col-topic">Topic</div>
                            <div class="tt-col-value-large hide-mobile">Up Voted</div>
                            <div class="tt-col-value-large hide-mobile" style="white-space: nowrap;">Down Voted</div>
                            <div class="tt-col-value-large hide-mobile">Comments</div>
                            <div class="tt-col-value-large hide-mobile">Status</div>
                        </div>
                        @forelse ($commentedPosts as $post)
                            {{-- Display commented post item --}}
                            <div class="tt-item">
                                <div class="tt-col-description">
                                    <h6 class="tt-title"><a href="{{ route('view-deal', ['id' => $post->id, 'title' => Str::slug($post->title)]) }}">
                                            {{ $post->title }}
                                        </a></h6>
                                    <div class="tt-col-message">
                                        {{ $post->description }}
                                    </div>
                                    {{-- Mobile display --}}
                                    <div class="row align-items-center no-gutters hide-desktope">
                                        <div class="col-9">
                                            <ul class="tt-list-badge">
                                                <li class="show-mobile"><a href="#">
                                                        @if ($post->status == 1)
                                                            <span class="badge bg-success px-3 py-2">Active</span>
                                                        @else
                                                            <span class="badge bg-secondary px-3 py-2">Inactive</span>
                                                        @endif
                                                    </a></li>
                                            </ul>
                                            {{-- <a href="#" class="tt-btn-icon show-mobile">
                                                <i class="tt-icon"><svg>
                                                        <use xlink:href="#icon-reply"></use>
                                                    </svg></i>
                                            </a> --}}
                                        </div>
                                        <div class="col-3 ml-auto show-mobile">
                                            <div class="tt-value">{{ $post->created_at->format('j M, y') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tt-col-value-large hide-mobile">{{ $post->upvotes }}</div>
                                <div class="tt-col-value-large hide-mobile">{{ $post->downvotes }}</div>
                                <div class="tt-col-value-large hide-mobile">{{ $post->comment_count }}</div>

                                <div class="tt-col-value-large hide-mobile">
                                    @if ($post->status == 1)
                                        <span class="badge bg-success px-3 py-2">Active</span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2">Inactive</span>
                                    @endif
                                </div>

                            </div>
                        @empty
                            <div class="tt-item">
                                <div class="tt-col-description">
                                    <p>You haven't commented on any deals yet.</p>
                                </div>
                            </div>
                        @endforelse
                        <br>
                        {{-- Pagination links for commented posts --}}
                        {{ $commentedPosts->links() }}
                    </div>
                </div>

                {{-- Voted Deals Pane --}}
                <div class="tab-pane fade" id="voted-pane" role="tabpanel" aria-labelledby="voted-tab">
                    <div class="tt-topic-list">
                        <div class="tt-list-header">
                            <div class="tt-col-topic">Topic</div>
                            <div class="tt-col-value-large hide-mobile">Up Voted</div>
                            <div class="tt-col-value-large hide-mobile" style="white-space: nowrap;">Down Voted</div>
                            <div class="tt-col-value-large hide-mobile">Comments</div>
                            <div class="tt-col-value-large hide-mobile">Status</div>
                        </div>
                        @forelse ($votedPosts as $post)
                            {{-- Display voted post item --}}
                            <div class="tt-item">
                                <div class="tt-col-description">
                                    <h6 class="tt-title"><a href="{{ route('view-deal', ['id' => $post->id, 'title' => Str::slug($post->title)]) }}">
                                            {{ $post->title }}
                                        </a></h6>
                                    <div class="tt-col-message">
                                        {{ $post->description }}
                                    </div>
                                    {{-- Mobile display --}}
                                    <div class="row align-items-center no-gutters hide-desktope">
                                        <div class="col-9">
                                            <ul class="tt-list-badge">
                                                <li class="show-mobile"><a href="#">
                                                        @if ($post->status == 1)
                                                            <span class="badge bg-success px-3 py-2">Active</span>
                                                        @else
                                                            <span class="badge bg-secondary px-3 py-2">Inactive</span>
                                                        @endif
                                                    </a></li>
                                            </ul>
                                            {{-- <a href="#" class="tt-btn-icon show-mobile">
                                                <i class="tt-icon"><svg>
                                                        <use xlink:href="#icon-reply"></use>
                                                    </svg></i>
                                            </a> --}}
                                        </div>
                                        <div class="col-3 ml-auto show-mobile">
                                            <div class="tt-value">{{ $post->created_at->format('j M, y') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tt-col-value-large hide-mobile">{{ $post->upvotes }}</div>
                                <div class="tt-col-value-large hide-mobile">{{ $post->downvotes }}</div>
                                <div class="tt-col-value-large hide-mobile">{{ $post->comment_count }}</div>

                                <div class="tt-col-value-large hide-mobile">
                                    @if ($post->status == 1)
                                        <span class="badge bg-success px-3 py-2">Active</span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2">Inactive</span>
                                    @endif
                                </div>


                            </div>
                        @empty
                            <div class="tt-item">
                                <div class="tt-col-description">
                                    <p>You haven't voted on any deals yet.</p>
                                </div>
                            </div>
                        @endforelse
                        <br>
                        {{-- Pagination links for voted posts --}}
                        {{ $votedPosts->links() }}
                    </div>
                </div>

                {{-- Subscribed Deals Pane --}}
                <div class="tab-pane fade" id="subscribed-pane" role="tabpanel" aria-labelledby="subscribed-tab">
                    <div class="tt-topic-list">
                        <div class="tt-list-header">
                            <div class="tt-col-topic">Topic</div>
                            <div class="tt-col-value-large hide-mobile">Up Voted</div>
                            <div class="tt-col-value-large hide-mobile" style="white-space: nowrap;">Down Voted</div>
                            <div class="tt-col-value-large hide-mobile">Comments</div>
                            <div class="tt-col-value-large hide-mobile">Status</div>
                        </div>
                        @forelse ($subscribedPosts as $post)
                            {{-- Display subscribed post item --}}
                            <div class="tt-item">
                                <div class="tt-col-description">
                                    <h6 class="tt-title"><a href="{{ route('view-deal', ['id' => $post->id, 'title' => Str::slug($post->title)]) }}">
                                            {{ $post->title }}
                                        </a></h6>
                                    <div class="tt-col-message">
                                        {{ $post->description }}
                                    </div>
                                    {{-- Mobile display --}}
                                    <div class="row align-items-center no-gutters hide-desktope">
                                        <div class="col-9">
                                            <ul class="tt-list-badge">
                                                <li class="show-mobile"><a href="#">
                                                        @if ($post->status == 1)
                                                            <span class="badge bg-success px-3 py-2">Active</span>
                                                        @else
                                                            <span class="badge bg-secondary px-3 py-2">Inactive</span>
                                                        @endif
                                                    </a></li>
                                            </ul>
                                            {{-- <a href="#" class="tt-btn-icon show-mobile">
                                                <i class="tt-icon"><svg>
                                                        <use xlink:href="#icon-reply"></use>
                                                    </svg></i>
                                            </a> --}}
                                        </div>
                                        <div class="col-3 ml-auto show-mobile">
                                            <div class="tt-value">{{ $post->created_at->format('j M, y') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tt-col-value-large hide-mobile">{{ $post->upvotes }}</div>
                                <div class="tt-col-value-large hide-mobile">{{ $post->downvotes }}</div>
                                <div class="tt-col-value-large hide-mobile">{{ $post->comment_count }}</div>

                                <div class="tt-col-value-large hide-mobile">
                                    @if ($post->status == 1)
                                        <span class="badge bg-success px-3 py-2">Active</span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2">Inactive</span>
                                    @endif
                                </div>


                            </div>
                        @empty
                            <div class="tt-item">
                                <div class="tt-col-description">
                                    <p>You haven't subscribed to any deals yet.</p>
                                </div>
                            </div>
                        @endforelse
                        <br>
                        {{-- Pagination links for subscribed posts --}}
                        {{ $subscribedPosts->links() }}
                    </div>
                </div>
            </div>
            {{-- ---------------------------- --}}

        </div>
    </div>
    {{-- --- End Tabs --- --}}

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

        @media(max-width:426px) {
            .nav-item-custom {
                width: 100%;
                padding-right: 0px !important;
            }

            .nav-item-button-custom {
                width: 100%;
            }


        }

        @media(max-width:767px) {
            .setting-btn-custom {
                width: fit-content !important;
                margin-left: auto;
            }



        }

        @media(min-width:426px) {
            .post-container-custom {
                min-height: 70vh;
            }


        }
    </style>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('#activityTabs button');
        const panes = document.querySelectorAll('#activityTabsContent .tab-pane');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Deactivate all tabs and panes
                tabs.forEach(t => t.classList.remove('active'));
                panes.forEach(p => p.classList.remove('show', 'active'));

                // Activate the clicked tab and its corresponding pane
                const targetPaneId = this.dataset.bsTarget || this.getAttribute('data-target');
                const targetPane = document.querySelector(targetPaneId);

                this.classList.add('active');
                if (targetPane) {
                    targetPane.classList.add('show', 'active');
                }
            });
        });

        const urlParams = new URLSearchParams(window.location.search);
        let activeTabId = 'commented-tab'; // Default tab

        if (urlParams.has('voted_page')) {
            activeTabId = 'voted-tab';
        } else if (urlParams.has('subscribed_page')) {
            activeTabId = 'subscribed-tab';
        }

        const initialTabButton = document.getElementById(activeTabId);
        if (initialTabButton) {
            initialTabButton.click(); // Simulate a click on the initial tab button
        }
    });
</script>
