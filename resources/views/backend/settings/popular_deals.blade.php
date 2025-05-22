@extends('backend.layout.layout')
@php
    $title = 'Popular Deals Formula';
    $subTitle = 'Popular Deals';
@endphp

@section('content')
    <div class="card h-full p-0 rounded-xl border-0 overflow-hidden">
        <div class="card-body p-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start"> {{-- Added gap-6 and items-start --}}

                {{-- --- Left Column: Explanation --- --}}
                <div class="col-span-12 lg:col-span-6"> {{-- Takes 6 columns on large screens, full width on small --}}
                    <div class="card border border-neutral-200 dark:border-neutral-600 p-6 h-full">
                        <h4 class="text-xl font-bold mb-4 text-neutral-800 dark:text-neutral-100">Understanding the
                            Popularity Score</h4>
                        <p class="text-neutral-700 dark:text-neutral-300 mb-4">
                            This formula calculates a "Popularity Score" for each deal, allowing to define what makes a
                            deal popular. It combines upvotes, downvotes, and comments, each with a
                            customizable "weight" or importance.
                        </p>
                        <h5 class="text-lg font-semibold mb-2 text-neutral-700 dark:text-neutral-200">The Formula:</h5>
                        <p
                            class="text-neutral-700 dark:text-neutral-300 font-mono bg-neutral-100 dark:bg-neutral-700 p-3 rounded mb-4">
                            <code class="block whitespace-pre-wrap">
                                Popularity Score = (Upvotes × Upvote Weight) + <br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Downvotes
                                × Downvote Weight) + <br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Comments
                                × Comment Weight)
                            </code>
                        </p>
                        <h5 class="text-lg font-semibold mb-2 text-neutral-700 dark:text-neutral-200">How Weights Work:</h5>
                        <ul class="list-disc list-inside text-neutral-700 dark:text-neutral-300 space-y-2">
                            <li>
                                <strong>Upvote Weight:</strong> A positive number (e.g., 1.0). Higher values mean each
                                upvote contributes more to the score.
                            </li>
                            <li>
                                <strong>Downvote Weight:</strong> A negative number (e.g., -0.5). Each downvote reduces the
                                score. A larger negative value means downvotes have a stronger negative impact.
                            </li>
                            <li>
                                <strong>Comment Weight:</strong> A positive number (e.g., 0.8). Each comment adds to the
                                score, reflecting engagement.
                            </li>
                        </ul>
                        <p class="text-neutral-700 dark:text-neutral-300 mt-4">
                            Adjust these weights to fine-tune how deals are ranked in the "Popular Deals" section.
                        </p>
                    </div>
                </div>
                {{-- --- End Left Column --- --}}

                {{-- --- Right Column: Form --- --}}
                <div class="col-span-12 lg:col-span-6"> {{-- Takes 6 columns on large screens, full width on small --}}
                    <div class="card border border-neutral-200 dark:border-neutral-600 p-6 h-full">
                        <div class="card-body">
                            <form action="{{ route('admin.popularDeals.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="mb-5">
                                    <label for="upvote_weight"
                                        class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                                        Upvote Weight</label>
                                    <input type="text" class="form-control rounded-lg" id="upvote_weight"
                                        name="popular_deal_upvote_weight" placeholder="Enter Upvote Weight"
                                        value="{{ (float) $settings['popular_deal_upvote_weight'] }}" required>
                                </div>

                                <div class="mb-5">
                                    <label for="downvote_weight"
                                        class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                                        Downvote Weight</label>
                                    <input type="text" class="form-control rounded-lg" id="downvote_weight"
                                        name="popular_deal_downvote_weight" placeholder="Enter Downvote Weight"
                                        value="{{ (float) $settings['popular_deal_downvote_weight'] }}" required>
                                </div>

                                <div class="mb-5">
                                    <label for="comment_weight"
                                        class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                                        Comment Weight</label>
                                    <input type="text" class="form-control rounded-lg" id="comment_weight"
                                        name="popular_deal_comment_weight" placeholder="Enter Comment Weight"
                                        value="{{ (float) $settings['popular_deal_comment_weight'] }}" required>
                                </div>


                                <div class="flex items-center justify-center gap-3">
                                    <button type="submit"
                                        class="btn btn-primary border border-primary-600 text-base px-14 py-3 rounded-lg">
                                        Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- --- End Right Column --- --}}

            </div> {{-- End grid --}}
        </div>
    </div>
@endsection
