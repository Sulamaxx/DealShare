@extends('layouts.app')

@section('title', 'Forum')

@section('content')
    <input type="text" id="post_id" value="{{ $post->id }}" hidden />
    <main id="tt-pageContent">
        <div class="container">
            <div class="tt-single-topic-list">
                <div class="tt-item">
                    <div class="tt-single-topic">
                        <div class="tt-item-header">
                            <div class="tt-item-info info-top">
                                <div class="tt-avatar-icon">
                                    <img src="{{ asset($post->user->profile_photo_path) }}" alt="">
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
                                    @auth
                                        <button class="badge bg-danger d-flex align-items-center" data-post-id="{{ $post->id }}"
                                            onclick="reportPost(this)">
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
                            <img class="mt-3" style="width: 100%;height: 400px;" src="{{ asset($post->image) }}"
                                alt="">
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
                            <a class="tt-icon-btn like-button {{ $vote_type === 'up' ? 'upvoted' : '' }}"
                                data-post-id="{{ $post->id }}" data-vote-type="up" onclick="vote(this)">
                                <i class="tt-icon"><svg>
                                        <use xlink:href="#icon-like"></use>
                                    </svg></i>
                                <span class="tt-text" id="up_vote_span">{{ $post->upvotes }}</span>
                            </a>
                            <a class="tt-icon-btn dislike-button {{ $vote_type === 'down' ? 'downvoted' : '' }}"
                                data-post-id="{{ $post->id }}" data-vote-type="down" onclick="vote(this)">
                                <i class="tt-icon"><svg>
                                        <use xlink:href="#icon-dislike"></use>
                                    </svg></i>
                                <span class="tt-text" id="down_vote_span">{{ $post->downvotes }}</span>
                            </a>

                            <!-- <a href="#" class="tt-icon-btn">
                                                                                                                                                        <i class="tt-icon"><svg>
                                                                                                                                                                <use xlink:href="#icon-favorite"></use>
                                                                                                                                                            </svg></i>
                                                                                                                                                        <span class="tt-text">{{ $post->comment_count }}</span>
                                                                                                                                                    </a> -->
                            <div class="col-separator"></div>
                            <button class="btn btn-success btn-sm">Subscribe</button>
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
    function reportPost(element) {
        // Get the post ID from the button's data attribute
        const postId = element.dataset.postId;
        const url = `/posts/${postId}/report`;

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to report this deal?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Report it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // User confirmed, send the report request
                fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Content-Type': 'application/json'
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Reported!',
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
                                text: data.message || 'Could not report the deal.',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error reporting post:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'An error occurred while reporting.',
                            timer: 3000,
                            showConfirmButton: false
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

    function renderComments(comments) {
        let html = '';
        comments.forEach(comment => {
            const replies = comment.replies_recursive || [];
            const hasReplies = replies.length > 0;
            const toggleId = `toggle-replies-${comment.id}`;

            html += `
            <div class="tt-item" data-id="${comment.id}">
                <div class="tt-single-topic">
                    <div class="tt-item-header pt-noborder">
                        <div class="tt-item-info info-top">
                            <div class="tt-avatar-icon"><i class="tt-icon"><svg><use xlink:href="#icon-ava-v"></use></svg></i></div>
                            <div class="tt-avatar-title"><a href="#">${comment.user?.name || 'Anonymous'}</a></div>
                            <a href="#" class="tt-info-time d-flex">
                                <i class="tt-icon d-flex justify-content-center align-items-center"><svg><use xlink:href="#icon-time"></use></svg></i>
                                ${comment.created_at}
                            </a>
                        </div>
                    </div>
                    <div class="tt-item-description">
                        ${comment.comment_text}
                        <div>
                            <a href="#" class="reply-btn" data-id="${comment.id}">Reply</a>
                            <div class="reply-form-container" id="reply-form-${comment.id}"></div>
                        </div>
                        ${hasReplies ? `
                            <a href="#" class="toggle-replies-btn" data-target="${toggleId}">Show Replies (${replies.length})</a>
                            <div class="replies" id="${toggleId}" style="display: none;">
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
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('reply-btn')) {
            e.preventDefault();
            const parentId = e.target.dataset.id;
            const form = `
            <form onsubmit="submitReply(event, ${parentId})">
                <textarea name="comment_text" required></textarea>
                <button type="submit">Reply</button>
            </form>
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
