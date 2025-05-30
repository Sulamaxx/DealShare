@extends('layouts.app')

@section('title', 'Buyme Bargians')

@section('content')
    <meta name="user-authenticated" content="{{ Auth::check() ? 'true' : 'false' }}">
    <style>
        .comment-item {
            font-family: 'Poppins', sans-serif;
        }

        .comment-actions {
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .comment-actions a {
            white-space: nowrap;
        }

        .comment-body p {
            margin-bottom: 0;
            line-height: 1.4;
        }

        /* Ensure tighter spacing */
        .user-info img {
            display: block;
            margin: 0 auto;
        }

        .user-info {
            font-size: 0.85rem;
            max-width: 60px;
            word-wrap: break-word;
        }

        .replies {
            margin-left: 1rem;
            border-left: 2px solid #ddd;
            padding-left: 1rem;
        }

        /* Mobile-specific tweaks */
        @media (max-width: 768px) {
            .comment-actions {
                flex-direction: column;
                align-items: flex-start;
            }

            .comment-actions a {
                margin-bottom: 5px;
            }

            .user-info {
                max-width: 100%;
                flex-direction: row;
                gap: 0.5rem;
                align-items: center;
            }
        }

        @media (max-width:426px) {
            .page-content-mobile {
                padding-top: 0px !important;
            }
        }
    </style>
    <input type="text" id="post_id" value="{{ $post->id }}" hidden />
    <main id="tt-pageContent" class="page-content-mobile">
        <div class="container">
            <div class="tt-single-topic-list">
                <div class="tt-item">
                    <div class="tt-single-topic">
                        <div class="tt-item-header">
                            <div class="tt-item-info info-top">
                                <div class="tt-avatar-icon" style="flex-shrink: 0;">
                                    @php
                                        $profilePhotoUrl =
                                            $post->user && $post->user->profile_photo_path
                                                ? asset('storage/' . $post->user->profile_photo_path)
                                                : asset('assets/images/user.png');
                                    @endphp
                                    <img style="height: 50px; width: 50px; border-radius: 50%;" src="{{ $profilePhotoUrl }}"
                                        alt="">
                                </div>
                                <div class="tt-avatar-title">
                                    <a href="javascript:void(0)" class="post-author-link"
                                        data-user-id="{{ $post->user->id }}">
                                        {{ $post->user->name }}
                                    </a>
                                </div>
                                <div id="js-popup-setting" class="tt-popup-settings" style="display: none;">
                                    {{-- Initially hidden --}}
                                    <div class="tt-btn-col-close">
                                        {{-- Close button --}}
                                        <a href="#" style="padding: 9px 0 22px;">
                                            <span class="tt-icon-text">
                                                User Profile
                                            </span>
                                            <span class="tt-icon-close" style="top: 17px;">
                                                {{-- Replace with your actual SVG icon for close --}}
                                                <svg>
                                                    <use xlink:href="#icon-cancel"></use>
                                                </svg>
                                            </span>
                                        </a>
                                    </div>

                                    {{-- Content area for the user profile details --}}
                                    <div class="tt-popup-settings-content"> {{-- Added a content wrapper --}}

                                        {{-- User Avatar Section --}}
                                        <div class="tt-form-upload"> {{-- Reusing a class from your form snippet --}}
                                            <div class="row no-gutter align-items-center"> {{-- Added align-items-center --}}
                                                <div class="col-auto">
                                                    <div class="tt-avatar">
                                                        {{--
                            This img tag will be populated by JavaScript.
                            Include a default src and alt.
                            The SVG placeholder can be included and toggled by JS/CSS.
                        --}}
                                                        <img id="avatarPreview" src="{{ asset('assets/images/user.png') }}"
                                                            {{-- Default image --}} alt="User Avatar"
                                                            style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                                                        {{-- Basic styling --}}

                                                        {{-- Optional: SVG placeholder, hidden if image is shown --}}
                                                        {{-- <svg id="avatarSvgPlaceholder" style="display: none;"><use xlink:href="#icon-ava-d"></use></svg> --}}
                                                    </div>
                                                </div>
                                                <div class="col-auto ml-auto">
                                                    {{-- This button/label is for the *authenticated* user's settings form.
                         You might want to hide this when viewing *other* users' profiles. --}}
                                                    {{-- <input type='file' id="imageUpload" name="image" accept=".png, .jpg, .jpeg" hidden> --}}
                                                    {{-- <label for="imageUpload" class="btn btn-primary">Upload Picture</label> --}}
                                                </div>
                                            </div>
                                        </div>

                                        {{-- User Name Display --}}
                                        <div class="form-group"> {{-- Reusing a class --}}
                                            <label class="inline-block font-bold text-neutral-600 text-sm mb-1">Name</label>
                                            {{-- This element will display the user's name --}}
                                            <p id="popupUserName" class="form-control-static">{{-- Name will be inserted here by JS --}}</p>
                                        </div>

                                        {{-- Badges Section --}}
                                        <div class="form-group"> {{-- Reusing a class --}}
                                            <label
                                                class="inline-block font-bold text-neutral-600  text-sm mb-1">Badge</label>
                                            {{-- This ul will contain the badges --}}
                                            <ul id="popupUserBadges" class="tt-list-badge d-flex align-items-center gap-1">
                                                {{-- Badges will be populated here by JS --}}
                                                {{-- Example structure for a badge:
                 <li>
                     <a href="#">
                         <span class="tt-color-default tt-badge">
                             <img src="..." alt="Badge Name" style="max-width: 20px; height: auto; vertical-align: middle; margin-right: 5px;">
                             Badge Name (Vote Count)
                         </span>
                     </a>
                 </li>
                 --}}
                                            </ul>
                                        </div>


                                        {{-- User Statistics --}}
                                        <div class="form-group"> {{-- Reusing a class --}}
                                            <label
                                                class="inline-block font-bold text-neutral-600  text-sm mb-1">Statistics</label>
                                            {{-- This element will display the combined stats --}}
                                            <p id="popupUserStats" class="form-control-static">{{-- Stats will be inserted here by JS --}}</p>
                                        </div>

                                        {{-- Joined Date --}}
                                        <div class="form-group"> {{-- Reusing a class --}}
                                            <label class="inline-block font-bold text-neutral-600  text-sm mb-1">Member
                                                Since</label>
                                            {{-- This element will display the joined date --}}
                                            <p id="popupUserJoinedDate" class="form-control-static">{{-- Joined date will be inserted here by JS --}}
                                            </p>
                                        </div>

                                        {{-- Last Seen --}}
                                        {{-- <div class="form-group"> {{-- Reusing a class
                                            <label class="inline-block font-bold text-neutral-600  text-sm mb-1">Last
                                                Seen</label>
                                            {{-- This element will display the last seen date
                                            <p id="popupUserLastSeen" class="form-control-static">{{-- Last seen will be inserted here by JS </p>
                                        </div>

                                        {{-- Location
                                        <div class="form-group"> {{-- Reusing a class
                                            <label
                                                class="inline-block font-bold text-neutral-600  text-sm mb-1">Location</label>
                                            {{-- This element will display the location
                                            <p id="popupUserLocation" class="form-control-static">{{-- Location will be inserted here by JS</p>
                                        </div> --}}

                                        {{--
            Add other fields here if needed, like email (be cautious with privacy),
            or any other user attributes you want to display in the popup.
            Make sure to add IDs or classes so the JavaScript can target them.
        --}}

                                        {{--
            Note: If your #js-popup-settings was originally a form for editing the *authenticated* user,
            it might contain input fields for name, email, checkboxes, etc.
            When displaying *another* user's profile, you should hide or disable these editing fields
            using CSS or JavaScript.
        --}}

                                    </div> {{-- End tt-popup-settings-content --}}

                                </div>
                                <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center">
                                    {{-- Created date remains in its own container --}}
                                    <a href="javascript:void(0)" class="tt-info-time d-flex justify-content-end mr-3">
                                        <i class="tt-icon d-flex align-items-center justify-content-center"><svg>
                                                <use xlink:href="#icon-time"></use>
                                            </svg></i>
                                        <span class="d-block">{{ $post->created_at }}</span>
                                    </a>
                                    {{-- New div for buttons, with margin-top for spacing on small screens only --}}
                                    <div class="d-flex align-items-center mt-2 mt-sm-0">
                                        @if ($post->helpful_by_user != 0)
                                            <button class="badge bg-success d-flex align-items-center mr-3">
                                                Helpful
                                            </button>
                                        @endif
                                        @auth
                                            <button class="badge bg-danger d-flex align-items-center"
                                                data-post-id="{{ $post->slug }}" onclick="reportPost(this)">
                                                Report Deal
                                            </button>
                                        @endauth
                                    </div>
                                </div>

                            </div>
                            <h3 class="tt-item-title">
                                <a href="javascript:void(0)">{{ $post->title }}</a>
                            </h3>
                            <h5 class="tt-item-category">
                                <a>{{ $post->category }}</a>
                            </h5>
                            <span class="badge bg-warning">New</span>
                            {{-- <img class="mt-3" style="width: 100%; height: 60vh; object-fit: cover;" src="{{ asset($post->image) }}" alt=""> --}}
                            <div id="image-viewer-{{ $post->id }}">
                                <img class="mt-3" style="width: 100%; height: 60vh; object-fit: cover; cursor: zoom-in;"
                                    src="{{ asset($post->image) }}" alt="{{ $post->title }}">
                            </div>


                        </div>
                        <div class="tt-item-description">
                            <p>
                                {{ $post->description }}
                            </p>

                            <p>
                                Link : <a href="{{ $post->link }}">{{ $post->link }}</a>
                            </p>
                        </div>
                        <div class="tt-item-info info-bottom">
                            @if (Auth::user())
                                <a class="tt-icon-btn like-button cursor-pointer {{ $vote_type === 'up' ? 'upvoted' : '' }}"
                                    data-post-id="{{ $post->id }}" data-vote-type="up" onclick="vote(this)">
                                    <i class="tt-icon"><svg>
                                            <use xlink:href="#icon-like"></use>
                                        </svg></i>
                                    <span class="tt-text" id="up_vote_span">{{ $post->upvotes }}</span>
                                </a>
                                <a class="tt-icon-btn dislike-button cursor-pointer {{ $vote_type === 'down' ? 'downvoted' : '' }}"
                                    data-post-id="{{ $post->id }}" data-vote-type="down" onclick="vote(this)">
                                    <i class="tt-icon"><svg>
                                            <use xlink:href="#icon-dislike"></use>
                                        </svg></i>
                                    <span class="tt-text" id="down_vote_span">{{ $post->downvotes }}</span>
                                </a>
                            @else
                                <a class="tt-icon-btn like-button cursor-pointer {{ $vote_type === 'up' ? 'upvoted' : '' }}"
                                    data-post-id="{{ $post->id }}" data-vote-type="up" onclick="loginMessage(this)">
                                    <i class="tt-icon"><svg>
                                            <use xlink:href="#icon-like"></use>
                                        </svg></i>
                                    <span class="tt-text" id="up_vote_span">{{ $post->upvotes }}</span>
                                </a>
                                <a class="tt-icon-btn dislike-button cursor-pointer {{ $vote_type === 'down' ? 'downvoted' : '' }}"
                                    data-post-id="{{ $post->id }}" data-vote-type="down"
                                    onclick="loginMessage(this)">
                                    <i class="tt-icon"><svg>
                                            <use xlink:href="#icon-dislike"></use>
                                        </svg></i>
                                    <span class="tt-text" id="down_vote_span">{{ $post->downvotes }}</span>
                                </a>
                            @endif

                            <a href="javascript:void(0)" class="tt-icon-btn">
                                <i class="tt-icon"><svg>
                                        <use xlink:href="#icon-reply"></use>
                                    </svg></i>
                                <span class="tt-text">{{ $post->comment_count }}</span>
                            </a>
                            <div class="col-separator"></div>

                            @php
                                $isFound = false;
                                $id = null;
                            @endphp

                            @foreach ($post->subscriptions as $item)
                                @if (Auth::user() && $item->user_id == Auth::user()->id)
                                    @php
                                        $isFound = true;
                                        $id = $item->id;
                                    @endphp
                                @endif
                            @endforeach

                            @if ($isFound)
                                <form action="{{ route('subscription.destroy') }}" method="post">
                                    @csrf
                                    <input type="text" name="id" value="{{ $id }}" hidden />
                                    <button class="btn btn-warning btn-sm" type="submit">Remove Subscribe</button>
                                </form>
                            @else
                                <form action="{{ route('subscription') }}" method="post">
                                    @csrf
                                    <input type="text" name="post_id" value="{{ $post->id }}" hidden />
                                    <button class="btn btn-success btn-sm" type="submit">Subscribe</button>
                                </form>
                            @endif

                        </div>
                    </div>
                </div>

                <div id="comments-container" data-post-id="{{ $post->id }}"></div>
                <script>
                    loadComments({{ $post->id }});
                </script>


            </div>

            <div class="tt-wrapper-inner">
                <div class="pt-editor form-default">
                    <h6 class="pt-title">Comment</h6>
                    <div class="form-group">
                        <textarea name="message" id="new_thread" class="form-control" rows="5"></textarea>
                    </div>
                    <div class="pt-row">
                        <div class="col-auto">
                        </div>
                        <div class="col-auto">
                            @if (Auth::user())
                                <a href="javascript:void(0)" onclick="StartNewThread()"
                                    class="btn btn-secondary btn-width-lg">Reply</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-secondary btn-width-lg">Login to
                                    Comment</a>
                            @endif

                        </div>
                    </div>
                </div>
            </div>


        </div>
    </main>



@endsection
<!-- Pusher & Echo CDN scripts -->
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.iife.js"></script>

<script>
    function loginMessage(x) {
        Swal.fire({
            icon: 'info',
            title: 'Info',
            text: "Please Login first to make your vote",
            timer: 3000,
            showConfirmButton: false
        });
        setTimeout(() => {
            window.location.href = "/login";
        }, 500);
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const image = document.getElementById('image-viewer-{{ $post->id }}');
        if (image) {
            new Viewer(image, {
                toolbar: false,
                navbar: false,
                title: false,
                movable: true,
                scalable: false,
                zoomable: true,
                transition: true,
            });
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Find all author links on the page
        const authorLinks = document.querySelectorAll('.post-author-link');

        // Add a click event listener to each author link
        authorLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default link behavior (navigating)

                // Get the user ID from the data attribute of the clicked link
                const userId = this.dataset.userId;

                // Check if userId was successfully retrieved
                if (!userId) {
                    console.error('User ID not found on the clicked link.');
                    return;
                }

                const profileDataUrl = `/users/${userId}/profile-data`;

                fetch(profileDataUrl)
                    .then(response => {

                        if (response.status === 403) {
                            return response.json().then(errorData => {
                                Swal.fire({
                                    icon: 'info', // Use info or warning icon for privacy
                                    title: 'Private Profile',
                                    text: errorData.error ||
                                        'This user\'s profile is private and cannot be viewed.',
                                });
                                // Return a rejected promise to stop further processing in this chain
                                return Promise.reject('Profile is private');
                            });
                        }

                        // Check if the response was successful (status code 2xx)
                        if (!response.ok) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: `Could not load user profile data.`,
                            });
                        }
                        // Parse the JSON response body
                        return response.json();
                    })
                    .then(userData => {

                        populateUserProfilePopup(userData);

                        showUserProfilePopup();
                    })
                    .catch(error => {

                        if (response.status === 403) {
                            Swal.fire({
                                icon: 'info', // Use info or warning icon for privacy
                                title: 'Private Profile',
                                text: errorData.error ||
                                    'This user\'s profile is private and cannot be viewed.',
                            });
                        }
                        // Handle any errors during the fetch operation (network issues, server errors, etc.)
                        console.error('Error fetching user profile data:', error);
                        // Optional: Display a user-friendly error message (e.g., using SweetAlert2)
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Could not load user profile data.',
                        });
                    });
            });
        });

        // --- Function to populate the popup HTML with data ---
        // This function assumes the structure of your #js-popup-settings div
        function populateUserProfilePopup(userData) {
            const popup = document.getElementById('js-popup-setting');
            if (!popup) {
                console.error('Popup element #js-popup-setting not found!');
                return;
            }

            // Populate Avatar
            const avatarImg = popup.querySelector('#avatarPreview');
            if (avatarImg) {
                avatarImg.src = userData.profile_photo_url || '{{ asset('assets/images/user.png') }}';
                avatarImg.alt = `${userData.name || 'User'}'s avatar`;
            }

            // Populate User Name
            const userNameElement = popup.querySelector('#popupUserName');
            if (userNameElement) {
                userNameElement.textContent = userData.name || 'Anonymous';
            }

            // Populate Badges
            const badgesList = popup.querySelector('#popupUserBadges');
            if (badgesList) {
                badgesList.innerHTML = ''; // Clear existing content
                if (userData.highest_qualifying_badge) {
                    const badge = userData.highest_qualifying_badge;
                    const badgeHtml = `
                <li>
                    <a href="#" title="${badge.description || ''}">
                        <span class="tt-color${badge.color || 'default'} tt-badge">
                            ${badge.icon_url ? `<img src="${badge.icon_url}" alt="${badge.name || ''}" style="max-width: 30px; height: auto; vertical-align: middle; margin-right: 5px;">` : ''}
                            ${badge.name || ''}
                        </span>
                    </a>
                </li>
            `;
                    badgesList.innerHTML = badgeHtml;
                } else {
                    badgesList.innerHTML = '<li><span class="tt-color-none tt-badge">No Badge Yet</span></li>';
                }
            }

            // Populate Statistics
            const statsElement = popup.querySelector('#popupUserStats');
            if (statsElement) {
                statsElement.textContent =
                    `Posts: ${userData.total_deals_submitted || 0} / Comments: ${userData.total_comments_made || 0}`;
            }

            // Populate Joined Date
            const joinedDateElement = popup.querySelector('#popupUserJoinedDate');
            if (joinedDateElement) {
                joinedDateElement.textContent = userData.joined_date || 'N/A';
            }

            /* // Populate Last Seen
            const lastSeenElement = popup.querySelector('#popupUserLastSeen');
            if (lastSeenElement) {
                lastSeenElement.textContent = userData.last_seen || 'N/A';
            }

            // Populate Location
            const locationElement = popup.querySelector('#popupUserLocation');
            if (locationElement) {
                locationElement.textContent = userData.location || 'Not specified';
            } */
        }

        function showUserProfilePopup() {
            const popup = document.getElementById('js-popup-setting');
            if (popup) {
                // Apply styles
                popup.style.position = 'fixed';
                popup.style.top = '50%';
                popup.style.left = '50%';
                popup.style.transform = 'translate(-50%, -50%)';
                popup.style.zIndex = '1000';
                popup.style.background = 'white';
                popup.style.padding = '20px';
                popup.style.border = '1px solid #ccc';
                popup.style.height = 'fit-content';
                popup.style.display = 'block';

                console.log('Popup display set to block');
            } else {
                console.error('Popup element not found when trying to show!');
            }
        }

        function hideUserProfilePopup() {
            const popup = document.getElementById('js-popup-setting');
            if (popup) {
                popup.style.display = 'none';
            }
        }

        // Add event listener to the close button of the popup
        const closeButton = document.querySelector('#js-popup-setting .tt-btn-col-close a');
        if (closeButton) {
            closeButton.addEventListener('click', function(e) {
                e.preventDefault();
                hideUserProfilePopup();
            });
        }

    });


    function reportPost(element) {
        const postId = element.dataset.postId;
        console.log(postId);
        const url = `/posts/${postId}/report`;

        Swal.fire({
            title: 'Report this Deal?',
            text: 'Please select a reason:',
            icon: 'warning',
            input: 'select',
            inputOptions: {
                'Spam / Fake Deal': 'Spam / Fake Deal',
                'Incorrect Information': 'Incorrect Information',
                'Expired Deal': 'Expired Deal'
            },
            inputPlaceholder: 'Select a reason',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Submit Report',
            showLoaderOnConfirm: true,
            preConfirm: (reason) => {
                if (!reason) {
                    Swal.showValidationMessage('Please select a reason.');
                    return false;
                }
                return reason;
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                const reason = result.value;

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            post_id: postId,
                            reason: reason
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            if (response.redirected) {
                                window.location.href = response.url;
                                return new Promise(() => {});
                            }
                            return response.json().then(data => {
                                throw new Error(data.message || `Server error: ${response.status}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Report Submitted!',
                                text: data.message,
                                timer: 3000,
                                showConfirmButton: false
                            });
                            element.disabled = true;
                            element.textContent = 'Reported';
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed!',
                                text: data.message || 'Could not submit the report.',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error submitting report:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: error.message || 'An error occurred while submitting the report.',
                        });
                    });
            }
        });
    }

    function reportComment(element) {
        const commentId = element.dataset.commentId;
        const url = `/comments/${commentId}/report`;

        Swal.fire({
            title: 'Report this Comment?',
            text: 'Please select a reason:',
            icon: 'warning',
            input: 'select',
            inputOptions: {
                '': 'Select a reason',
                'Spam': 'Spam',
                'Inappropriate language': 'Inappropriate language',
                'Misinformation': 'Misinformation'
            },
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Submit Report',
            showLoaderOnConfirm: true,
            preConfirm: (reason) => {
                if (!reason) {
                    Swal.showValidationMessage('Please select a reason.');
                    return false;
                }
                return reason;
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                const reason = result.value;

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            comment_id: commentId,
                            reason: reason
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            if (response.redirected) {
                                window.location.href = response.url;
                                return new Promise(() => {});
                            }
                            return response.json().then(data => {
                                throw new Error(data.message || `Server error: ${response.status}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Report Submitted!',
                                text: data.message,
                                timer: 3000,
                                showConfirmButton: false
                            });
                            element.disabled = true;
                            element.textContent = 'Reported';
                        } else {
                            // This block would be for custom 'success: false' responses from the server
                            // like the 'Already reported' case (which we handle in .catch now)
                            // or validation errors if not caught by preConfirm
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed!',
                                text: data.message || 'Could not submit the report.',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        }
                    })
                    .catch(error => {
                        // This block handles network errors OR errors thrown from the .then blocks
                        console.error('Error submitting report:', error);

                        // Display the error message (either from thrown Error or a generic one)
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: error.message || 'An error occurred while submitting the report.',
                        });
                    });
            }
        })
    }

    function StartNewThread() {
        const comment = document.getElementById('new_thread').value;
        const post_id = document.getElementById('post_id').value;

        fetch('/start-thread', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    comment: comment,
                    post_id: post_id
                })
            })
            .then(res => res.json())
            .then(data => {
                // console.log(data);
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        timer: 3000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message,
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
                window.location.reload();
            })
            .catch(error => console.log(error));
    }


    // Replace with your own values
    const POST_ID = "{{ $post->id }}";
    const PUSHER_APP_KEY = "{{ env('PUSHER_APP_KEY') }}";
    const PUSHER_CLUSTER = "{{ env('PUSHER_APP_CLUSTER') }}";

    Pusher.logToConsole = false;

    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: PUSHER_APP_KEY,
        cluster: PUSHER_CLUSTER,
        encrypted: true
    });

    window.Echo.channel('post.' + POST_ID)
        .listen('.PostVoted', (e) => {
            // Update counts in real time
            document.getElementById('up_vote_span').textContent = e.post.upvotes;
            document.getElementById('down_vote_span').textContent = e.post.downvotes;
        });



    function vote(element) {

        const postId = element.dataset.postId;

        const voteType = element.dataset.voteType;
        const url = `/posts/${postId}/vote`;

        fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    vote_type: voteType,
                    post_id: postId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                } else {
                    const upvoteElement = document.getElementById('up_vote_span');
                    if (upvoteElement && data.upvotes !== undefined) {
                        upvoteElement.textContent = data.upvotes;
                    }

                    const downvoteElement = document.getElementById('down_vote_span');
                    if (downvoteElement && data.downvotes !== undefined) {
                        downvoteElement.textContent = data.downvotes;
                    }

                    const likeButton = document.querySelector('.like-button');
                    const dislikeButton = document.querySelector('.dislike-button');

                    if (likeButton) {
                        likeButton.classList.remove('upvoted');
                    }
                    if (dislikeButton) {
                        dislikeButton.classList.remove('downvoted');
                    }

                    if (voteType === 'up') {
                        if (likeButton) {
                            likeButton.classList.add('upvoted');
                        }
                    } else if (voteType === 'down') {
                        if (dislikeButton) {
                            dislikeButton.classList.add('downvoted');
                        }
                    }
                }

            })
            .catch(error => {
                console.error('Error voting:', error);
            });
    }
</script>

<script>
    function loadComments(postId) {
        fetch(`/comments/${postId}`)
            .then(res => res.json())
            .then(data => {
                console.log(data);
                const container = document.getElementById('comments-container');
                container.innerHTML = renderComments(data);
            })
            .catch(err => console.error(err));
    }

    // function renderComments(comments) {
    //     let html = '';
    //     comments.forEach(comment => {
    //         const replies = comment.replies_recursive || [];
    //         const hasReplies = replies.length > 0;
    //         const toggleId = `toggle-replies-${comment.id}`;

    //         html += `
    //         <div class="tt-item" data-id="${comment.id}">
    //             <div class="tt-single-topic">
    //                 <div class="tt-item-header pt-noborder">
    //                     <div class="tt-item-info info-top">
    //                         <div class="tt-avatar-icon"><i class="tt-icon"><svg><use xlink:href="#icon-ava-v"></use></svg></i></div>
    //                         <div class="tt-avatar-title"><a href="#">${comment.user?.name || 'Anonymous'}</a></div>
    //                         <a href="#" class="tt-info-time d-flex">
    //                             <i class="tt-icon d-flex justify-content-center align-items-center"><svg><use xlink:href="#icon-time"></use></svg></i>
    //                             ${comment.created_at}
    //                         </a>
    //                     </div>
    //                 </div>
    //                 <div class="tt-item-description">
    //                     ${comment.comment_text}
    //                     <div>
    //                         <a href="#" class="reply-btn" data-id="${comment.id}">Reply</a>
    //                         <div class="reply-form-container" id="reply-form-${comment.id}"></div>
    //                     </div>
    //                     ${hasReplies ? `
    //                         <a href="#" class="toggle-replies-btn" data-target="${toggleId}">Show Replies (${replies.length})</a>
    //                         <div class="replies" id="${toggleId}" style="display: none;">
    //                             ${renderComments(replies)}
    //                         </div>
    //                     ` : ''}
    //                 </div>
    //             </div>
    //         </div>
    //     `;
    //     });
    //     return html;
    // }

    function renderComments(comments) {
        let html = '';
        comments.forEach(comment => {
            const replies = comment.replies_recursive || [];
            const hasReplies = replies.length > 0;
            const toggleId = `toggle-replies-${comment.id}`;

            let photoPath = 'https://bargains.buyme.lk/assets/images/user.png';
            if (comment.user?.profile_photo_path) {
                photoPath = `${APP_STORAGE_URL}/${comment.user.profile_photo_path}`;
            }

            let formattedDate = '';
            if (comment.created_at) {
                const date = new Date(comment.created_at); // Parse the ISO string into a Date object

                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
                const day = String(date.getDate()).padStart(2, '0');
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                const seconds = String(date.getSeconds()).padStart(2, '0');

                formattedDate = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
            }

            html += `
        <div class="comment-item p-3 mb-3 bg-light rounded shadow-sm" data-id="${comment.id}">
            <div class="d-flex gap-3 align-items-start flex-wrap">
                <!-- User Info -->
                <div class="user-info text-center" style="width: 60px;">
                    <img src="${photoPath}" class="rounded mb-1 post-author-link" width="50" height="50" alt="avatar" data-user-id="${ comment.user?.id }" style="width:50px;height:50px;">
                    <div class="small fw-semibold post-author-link" data-user-id="${ comment.user?.id }">${comment.user?.name || 'Anonymous'}</div>
                    <!-- <div class="text-muted small">Cred: ${comment.user?.credit || 0}</div> -->
                </div>

                <!-- Comment Content -->
                <div class="flex-grow-1">
                    <div class="text-muted small mb-1">
                        Posted ${formattedDate}
                    </div>
                    <div class="comment-body mb-2">
                        <p class="mb-1">${comment.comment_text}</p>
                    </div>
                    <div class="comment-actions d-flex flex-wrap align-items-center gap-3 small">
                        <!-- <a href="#" class="vote-btn upvote text-decoration-none d-flex align-items-center"> -->
                        <!--     👍 <span class="ms-1">Vote</span> -->
                        <!-- </a> -->
                        <a href="javascript:void(0)" class="report-btn text-decoration-none" data-comment-id="${comment.id}" onclick="reportComment(this)">Report Spam</a>
                        <!-- <a href="#" class="quote-btn text-decoration-none">Quote</a> -->
                        <a href="#" class="reply-btn text-decoration-none ms-auto" data-id="${comment.id}">Reply</a>
                        ${hasReplies ? `
                            <a href="#" class="toggle-replies-btn text-decoration-none ms-3" data-target="${toggleId}">
                                Show Replies (${replies.length})
                            </a>
                        ` : ''}
                    </div>
                    <div class="reply-form-container mt-3" id="reply-form-${comment.id}"></div>
                    ${hasReplies ? `
                        <div class="replies mt-3 border-start ps-3" id="${toggleId}" style="display: none;">
                            ${renderComments(replies)}
                        </div>
                    ` : ''}
                </div>
            </div>
        </div>
        `;
        });
        return html;
    }


    document.addEventListener('click', function(e) {
        // Expand/collapse replies
        if (e.target.classList.contains('toggle-replies-btn')) {
            e.preventDefault();
            const targetId = e.target.dataset.target;
            const repliesDiv = document.getElementById(targetId);
            if (repliesDiv.style.display === 'none') {
                repliesDiv.style.display = 'block';
                e.target.textContent = 'Hide Replies';
            } else {
                repliesDiv.style.display = 'none';
                e.target.textContent = `Show Replies (${repliesDiv.children.length})`;
            }
        }
    });
</script>

<script>
    // document.addEventListener('click', function(e) {
    //     if (e.target.classList.contains('reply-btn')) {
    //         e.preventDefault();
    //         const parentId = e.target.dataset.id;
    //         const form = `
    //         <form onsubmit="submitReply(event, ${parentId})">
    //             <textarea name="comment_text" required></textarea>
    //             <button type="submit">Reply</button>
    //         </form>
    //     `;
    //         document.getElementById('reply-form-' + parentId).innerHTML = form;
    //     }
    // });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('reply-btn')) {
            e.preventDefault();
            const parentId = e.target.dataset.id;
            const form = `
            <div class="tt-wrapper-inner">
                <div class="pt-editor form-default">
                    <h6 class="pt-title">Post Your Reply</h6>
                    <form onsubmit="submitReply(event, ${parentId})">
                        <div class="form-group">
                            <textarea name="comment_text" class="form-control" rows="5" placeholder="Let's get started" required></textarea>
                        </div>
                        <div class="pt-row">
                            <div class="col-auto"></div>
                            <div class="col-auto">
                                ${document.querySelector('meta[name="user-authenticated"]').getAttribute('content') === 'true' ? `
                                    <button type="submit" class="btn btn-secondary btn-width-lg">Reply</button>
                                ` : `
                                    <a href="/login" class="btn btn-secondary btn-width-lg">Login to Comment</a>
                                `}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        `;
            document.getElementById('reply-form-' + parentId).innerHTML = form;
        }
    });



    function submitReply(event, parentId) {
        event.preventDefault();
        const textarea = event.target.querySelector('textarea');
        const commentText = textarea.value;
        const postId = document.getElementById('comments-container').dataset.postId;

        fetch('/comments', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    comment_text: commentText,
                    parent_id: parentId,
                    post_id: postId
                })
            })
            .then(res => res.json())
            .then((data) => {
                console.log(data);
                loadComments(postId);
            });
    }
</script>
