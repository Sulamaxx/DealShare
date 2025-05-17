@extends('layouts.app')

@section('title', 'Forum')

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
    </style>
    <input type="text" id="post_id" value="{{ $post->id }}" hidden />
    <main id="tt-pageContent">
        <div class="container">
            <div class="tt-single-topic-list">
                <div class="tt-item">
                    <div class="tt-single-topic">
                        <div class="tt-item-header">
                            <div class="tt-item-info info-top">
                                <div class="tt-avatar-icon">
                                    <img style="height: 50px; width: 50px; border-radius: 50%;"
                                        src="{{ asset($post->user->profile_photo_path) }}" alt="">
                                </div>
                                <div class="tt-avatar-title">
                                    <a href="javascript:void(0)">{{ $post->user->name }}</a>
                                </div>
                                <div class="d-flex align-items-center">
                                    <a href="javascript:void(0)" class="tt-info-time d-flex justify-content-end mr-3">
                                        <i class="tt-icon d-flex align-items-center justify-content-center"><svg>
                                                <use xlink:href="#icon-time"></use>
                                            </svg></i>
                                        <span class="d-block">{{ $post->created_at }}</span>
                                    </a>
                                    @if ($post->helpful_by_user != 0)
                                        <button class="badge bg-success d-flex align-items-center mr-3">
                                            Helpfull
                                        </button>
                                    @endif
                                    @auth
                                        <button class="badge bg-danger d-flex align-items-center"
                                            data-post-id="{{ $post->id }}" onclick="reportPost(this)">
                                            Report Deal
                                        </button>
                                    @endauth
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
                                    data-post-id="{{ $post->id }}" data-vote-type="down" onclick="loginMessage(this)">
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
                    <h6 class="pt-title">Post Your Reply</h6>
                    <div class="form-group">
                        <textarea name="message" id="new_thread" class="form-control" rows="5" placeholder="Lets get started"></textarea>
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
    function reportPost(element) {
        const postId = element.dataset.postId;
        const url = `/posts/${postId}/report`;

        Swal.fire({
            title: 'Report this Deal?',
            text: 'Please provide a brief reason:',
            icon: 'warning',
            input: 'textarea',
            inputPlaceholder: 'Enter your reason here...',
            inputAttributes: {
                'aria-label': 'Enter your reason here'
            },
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Submit Report',
            showLoaderOnConfirm: true,


            preConfirm: (reason) => {

                if (!reason && false) { // Change 'false' to true if reason is REQUIRED
                    Swal.showValidationMessage('Please provide a reason.');
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

            html += `
        <div class="comment-item p-3 mb-3 bg-light rounded shadow-sm" data-id="${comment.id}">
            <div class="d-flex gap-3 align-items-start flex-wrap">
                <!-- User Info -->
                <div class="user-info text-center" style="width: 60px;">
                    <img src="${comment.user?.profile_photo_path || 'https://via.placeholder.com/50'}" class="rounded mb-1" width="50" height="50" alt="avatar">
                    <div class="small fw-semibold">${comment.user?.name || 'Anonymous'}</div>
                    <!-- <div class="text-muted small">Cred: ${comment.user?.credit || 0}</div> -->
                </div>

                <!-- Comment Content -->
                <div class="flex-grow-1">
                    <div class="text-muted small mb-1">
                        Posted ${comment.created_at}
                    </div>
                    <div class="comment-body mb-2">
                        <p class="mb-1">${comment.comment_text}</p>
                    </div>
                    <div class="comment-actions d-flex flex-wrap align-items-center gap-3 small">
                        <!-- <a href="#" class="vote-btn upvote text-decoration-none d-flex align-items-center"> -->
                        <!--     👍 <span class="ms-1">Vote</span> -->
                        <!-- </a> -->
                        <a href="#" class="report-btn text-decoration-none">Report Spam</a>
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
