@extends('backend.layout.layout')
@php
    $title = 'Comment Reports List';
    $subTitle = 'Comment Reports List';
    $script = '<script>
        $(".remove-item-btn").on("click", function() {
            $(this).closest("tr").addClass("hidden")
        });
    </script>';
@endphp

@section('content')
    <div class="grid grid-cols-12">
        <div class="col-span-12">
            <div class="card h-full p-0 rounded-xl border-0 overflow-hidden">
                <div
                    class="card-header border-b border-neutral-200 dark:border-neutral-600 bg-white dark:bg-neutral-700 py-4 px-6 flex items-center flex-wrap gap-3 justify-between">
                    <div class="flex items-center flex-wrap gap-3">
                        <form method="GET" action="{{ route('commentReportsList') }}"
                            class="flex items-center flex-wrap gap-4 mb-6">
                            <div class="row flex flex-row gap-3">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="form-control w-48 bg-white dark:bg-neutral-700" placeholder="Search deals...">

                                <select name="status" class="form-select w-32 dark:bg-neutral-700 dark:text-white">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>
                                        Reviewed
                                    </option>
                                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>
                                        Resolved
                                    </option>
                                </select>

                                <button type="submit" class="btn btn-primary btn-sm">
                                    <iconify-icon icon="ion:search-outline"></iconify-icon> Filter
                                </button>

                                <a href="{{ route('commentReportsList') }}" class="btn btn-secondary btn-sm">
                                    <iconify-icon icon="ph:arrow-counter-clockwise"></iconify-icon> Reset
                                </a>
                            </div>
                        </form>
                    </div>
                    {{-- <a href="{{ route('addReport') }}"
                        class="btn btn-primary text-sm btn-sm px-3 py-3 rounded-lg flex items-center gap-2">
                        <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                        Report A Deal
                    </a> --}}
                </div>
                <div class="card-body p-6">
                    <div class="table-responsive scroll-sm">
                        <table class="table bordered-table sm-table mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Reported On</th>
                                    <th>Comment</th>
                                    <th>Reason</th>
                                    <th>Report By</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reports as $index => $report)
                                    <tr>
                                        <td>{{ $reports->firstItem() + $index }}</td>
                                        <td>{{ $report->created_at->format('d M Y') }}</td>
                                        <td>{{ $report->reportable->comment_text ?? 'Comment not found' }}</td>
                                        <td>{{ $report->reason }}</td>
                                        <td>{{ $report->user->name ?? '-' }}</td>
                                        <td class="text-center">
                                            @if ($report->status == 'resolved')
                                                <span
                                                    class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 border border-success-600 px-6 py-1.5 rounded font-medium text-sm">Resolved</span>
                                            @elseif ($report->status == 'pending')
                                                <span
                                                    class="bg-red-200 dark:bg-red-600 text-red-600 border border-red-400 px-6 py-1.5 rounded font-medium text-sm">Pending</span>
                                            @else
                                                <span
                                                    class="bg-neutral-200 dark:bg-neutral-600 text-neutral-600 border border-neutral-400 px-6 py-1.5 rounded font-medium text-sm">Reviewed</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="flex items-center gap-3 justify-center">
                                                <form action="{{ route('commentReport.status', $report->id) }}"
                                                    method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button title="status" type="submit"
                                                        class="{{ $report->status == 'reviewed' || $report->status == 'resolved' ? 'bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 hover:bg-success-200' : 'bg-dark-100 dark:bg-dark-600/25 text-dark-600 dark:text-dark-400 hover:bg-dark-200' }} font-medium w-10 h-10 flex justify-center items-center rounded-full">
                                                        <iconify-icon icon="mdi:toggle-switch"
                                                            class="menu-icon"></iconify-icon>
                                                    </button>
                                                </form>
                                                {{-- <form method="GET"
                                                    action="{{ route('comment.edit', $report->reportable_id) }}">
                                                    @csrf
                                                    <button type="submit"
                                                        class="bg-info-100 dark:bg-info-600/25 hover:bg-info-200 text-info-600 dark:text-info-400 font-medium w-10 h-10 flex justify-center items-center rounded-full">
                                                        <iconify-icon icon="lucide:edit"
                                                            class="icon text-xl"></iconify-icon>
                                                    </button>
                                                </form> --}}
                                                @if ($report->reportable && $report->reportable->post_id)
                                                <a title="view deal" href="{{ route('view-deal', $report->reportable->post_id) }}"
                                                    class="bg-blue-100 dark:bg-blue-600/25 hover:bg-blue-200 text-blue-600 dark:text-blue-400 font-medium w-10 h-10 flex justify-center items-center rounded-full"
                                                    title="View Original Post" target="_blank">
                                                    <iconify-icon icon="mdi:post-outline"
                                                    class="icon text-xl"></iconify-icon> {{-- Example icon --}}
                                                </a>
                                                @endif
                                                <button title="edit comment" type="button"
                                                    class="bg-info-100 dark:bg-info-600/25 hover:bg-info-200 text-info-600 dark:text-info-400 font-medium w-10 h-10 flex justify-center items-center rounded-full edit-comment-btn"
                                                    data-comment-id="{{ $report->reportable_id }}" title="Edit Comment">
                                                    <iconify-icon icon="lucide:edit" class="icon text-xl"></iconify-icon>
                                                </button>
                                                {{-- <button type="button"
                                                    class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 bg-hover-success-200 font-medium w-10 h-10 flex justify-center items-center rounded-full">
                                                    <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                                </button> --}}
                                                {{-- <button type="button"
                                                    class="remove-item-btn bg-danger-100 dark:bg-danger-600/25 hover:bg-danger-200 text-danger-600 dark:text-danger-500 font-medium w-10 h-10 flex justify-center items-center rounded-full">
                                                    <iconify-icon icon="fluent:delete-24-regular"
                                                        class="menu-icon"></iconify-icon>
                                                </button> --}}
                                                <form
                                                    action="{{ route('commentReport.deactivate', ['report' => $report->id, 'comment' => $report->reportable_id]) }}"
                                                    method="POST" class="inline-block"
                                                    onsubmit="return confirm('Are you sure you want to deactivate the reported comment?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button title="deactivate comment" type="submit"
                                                        class="remove-item-btn bg-danger-100 dark:bg-danger-600/25 hover:bg-danger-200 text-danger-600 dark:text-danger-500 font-medium w-10 h-10 flex justify-center items-center rounded-full">
                                                        <iconify-icon icon="ic:outline-power-settings-new"
                                                            class="menu-icon"></iconify-icon>
                                                    </button>
                                                </form>
                                                <form action="{{ route('report.delete', $report->id) }}" method="POST"
                                                    class="inline-block"
                                                    onsubmit="return confirm('Are you sure you want to delete this report?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button title="delete" type="submit"
                                                        class="remove-item-btn bg-danger-100 dark:bg-danger-600/25 hover:bg-danger-200 text-danger-600 dark:text-danger-500 font-medium w-10 h-10 flex justify-center items-center rounded-full">
                                                        <iconify-icon icon="fluent:delete-24-regular"
                                                            class="menu-icon"></iconify-icon>
                                                    </button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No reports found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="flex items-center justify-between flex-wrap gap-2 mt-6">
                        {{-- <span class="text-sm">Showing {{ $deals->firstItem() }} to {{ $deals->lastItem() }} of
                            {{ $deals->total() }} deals</span> --}}
                        {{ $reports->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .swal2-confirm {
            background-color: #a199f1;
        }

        .swal2-cancel {
            background-color: #9fabb2;
        }

        .swal2-deny {
            background-color: #a90410;
        }
    </style>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {



        // --- Handle Inline Comment Edit with SweetAlert2 ---
        document.querySelectorAll('.edit-comment-btn').forEach(button => {
            button.addEventListener('click', function() {
                const commentId = this.dataset
                    .commentId; // Get the comment ID from the data attribute

                // --- Show the SweetAlert2 dialog with a textarea input ---
                Swal.fire({
                    title: 'Edit Comment',
                    input: 'textarea', // Use a textarea input type
                    inputLabel: 'Edit the comment content:',
                    inputPlaceholder: 'Enter the updated comment text...',
                    inputValue: '', // Initial value (can be fetched via AJAX if needed)
                    showCancelButton: true,
                    confirmButtonText: 'Save Changes',
                    showLoaderOnConfirm: true, // Show a loader while the update is processing
                    preConfirm: (commentText) => {
                        // This function runs before confirming.
                        // You can add validation here if the text is required or has length constraints.
                        if (!commentText || commentText.trim() === '') {
                            Swal.showValidationMessage(
                                'Comment text cannot be empty.');
                            return false; // Prevent closing the dialog
                        }
                        return commentText; // Return the edited text
                    },
                    allowOutsideClick: () => !Swal
                        .isLoading(),
                    customClass: {
                        container: 'swal2-buttons'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const updatedCommentText = result.value;

                        // --- Send AJAX request to update the comment ---
                        // Assuming your backend route for updating a comment via AJAX is PUT /backend/comments/{comment}
                        const updateUrl =
                            `/admin/deals/comments/${commentId}`; // Adjust URL if needed

                        fetch(updateUrl, {
                                method: 'PUT', // Or 'PATCH'
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token
                                },
                                body: JSON.stringify({
                                    comment_text: updatedCommentText
                                })
                            })
                            .then(response => {
                                if (!response.ok) {
                                    // If the response is not OK (e.g., 422 validation error, 500 server error)
                                    return response.json().then(data => {
                                        // Throw an error with the message from the backend
                                        throw new Error(data.message ||
                                            'Failed to update comment.');
                                    });
                                }
                                // If successful, parse the JSON response
                                return response.json();
                            })
                            .then(data => {
                                // --- Handle success response ---
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Updated!',
                                        text: data.message ||
                                            'Comment updated successfully.',
                                        timer: 2000, // Auto-close after 2 seconds
                                        showConfirmButton: false
                                    });

                                    // Optional: Update the comment text in the table row without a full page reload
                                    // You'll need to find the element displaying the comment text for this report.
                                    // This requires adding a specific class or ID to the comment text cell in the table.
                                    // Example (assuming a class 'comment-text-cell' on the <td>):
                                    // const commentTextCell = button.closest('tr').querySelector('.comment-text-cell');
                                    // if (commentTextCell) {
                                    //     commentTextCell.textContent = updatedCommentText; // Update the displayed text
                                    // }

                                    // For simplicity, a page reload is often easiest after an inline edit:
                                    window.location.reload();

                                } else {
                                    // Handle backend logic indicating failure even with 200 status
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Failed!',
                                        text: data.message ||
                                            'Could not update comment.',
                                    });
                                }
                            })
                            .catch(error => {
                                // --- Handle network errors or errors thrown from .then ---
                                console.error('Error updating comment:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: error.message ||
                                        'An error occurred while updating the comment.',
                                });
                            });
                    }
                }); // End Swal.fire .then()
            }); // End button click listener
        }); // End forEach button


        // Optional: Fetch initial comment text when the edit button is clicked
        // Uncomment this block if you want the textarea to be pre-filled with the current comment text.
        /*
        document.querySelectorAll('.edit-comment-btn').forEach(button => {
            button.addEventListener('click', function() {
                const commentId = this.dataset.commentId;
                const fetchUrl = `/backend/comments/${commentId}/text`; // Assuming this route exists

                fetch(fetchUrl)
                    .then(response => {
                        if (!response.ok) {
                             return response.json().then(data => { throw new Error(data.message || 'Failed to fetch comment text.'); });
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Now show the Swal dialog with the fetched text
                         Swal.fire({
                            title: 'Edit Comment',
                            input: 'textarea',
                            inputLabel: 'Edit the comment content:',
                            inputPlaceholder: 'Enter the updated comment text...',
                            inputValue: data.comment_text, // Use the fetched text here
                            showCancelButton: true,
                            confirmButtonText: 'Save Changes',
                            showLoaderOnConfirm: true,
                            preConfirm: (commentText) => {
                                if (!commentText || commentText.trim() === '') {
                                    Swal.showValidationMessage('Comment text cannot be empty.');
                                    return false;
                                }
                                return commentText;
                            },
                            allowOutsideClick: () => !Swal.isLoading()
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const updatedCommentText = result.value;
                                // ... (AJAX fetch request to update the comment, same as above) ...
                                const updateUrl = `/backend/comments/${commentId}`; // Adjust URL if needed

                                fetch(updateUrl, {
                                    method: 'PUT', // Or 'PATCH'
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        comment_text: updatedCommentText
                                    })
                                })
                                .then(response => {
                                    if (!response.ok) {
                                         return response.json().then(data => { throw new Error(data.message || 'Failed to update comment.'); });
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire({ icon: 'success', title: 'Updated!', text: data.message || 'Comment updated successfully.', timer: 2000, showConfirmButton: false });
                                        window.location.reload(); // Or update table row
                                    } else {
                                        Swal.fire({ icon: 'error', title: 'Failed!', text: data.message || 'Could not update comment.' });
                                    }
                                })
                                .catch(error => {
                                    console.error('Error updating comment:', error);
                                    Swal.fire({ icon: 'error', title: 'Error!', text: error.message || 'An error occurred while updating the comment.' });
                                });
                            }
                        }); // End inner Swal.fire .then()
                    })
                    .catch(error => {
                         console.error('Error fetching comment text:', error);
                         Swal.fire({
                             icon: 'error',
                             title: 'Error',
                             text: error.message || 'Could not fetch comment text for editing.',
                         });
                    });
            });
        });
        */

    }); // End DOMContentLoaded
</script>
