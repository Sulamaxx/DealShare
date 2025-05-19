@extends('backend.layout.layout')
@php
    $title = 'Highly Voted Deals Formula';
    $subTitle = 'Highly Voted Deals';
@endphp

@section('content')
    <div class="card h-full p-0 rounded-xl border-0 overflow-hidden">
        <div class="card-body p-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 justify-center">
                <div class="col-span-12 lg:col-span-10 xl:col-span-8 2xl:col-span-6 2xl:col-start-4">
                    <div class="card border border-neutral-200 dark:border-neutral-600">
                        <div class="card-body">
                            <form action="{{ route('admin.popularDeals.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf


                                <!-- Upvote Weight Input -->
                                <div class="mb-5">
                                    <label for="upvote_weight"
                                        class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                                        Upvote Weight</label>
                                    <input type="text" class="form-control rounded-lg" id="upvote_weight"
                                        name="popular_deal_upvote_weight" placeholder="Enter Upvote Weight"
                                        value="{{ (float) $settings['popular_deal_upvote_weight'] }}" required>
                                </div>

                                <!-- Downvote Weight Input -->
                                <div class="mb-5">
                                    <label for="downvote_weight"
                                        class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                                        Downvote Weight</label>
                                    <input type="text" class="form-control rounded-lg" id="downvote_weight"
                                        name="popular_deal_downvote_weight" placeholder="Enter Downvote Weight"
                                        value="{{ (float) $settings['popular_deal_downvote_weight'] }}" required>
                                </div>

                                <!-- Comment Weight Input -->
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
            </div>
        </div>
    </div>
@endsection
