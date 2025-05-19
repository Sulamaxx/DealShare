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
                            <form action="{{ route('admin.highlyVotedDeals.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf


                                <!-- Upvote Weight Input -->
                                <div class="mb-5">
                                    <label for="upvote_count"
                                        class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                                        Upvote Count</label>
                                    <input type="text" class="form-control rounded-lg" id="upvote_count"
                                        name="highly_voted_deal_upvote_count" placeholder="Enter Upvote Count"
                                        value="{{ $settings['highly_voted_deal_upvote_count'] ? (float) $settings['highly_voted_deal_upvote_count'] : '' }}"
                                        required>
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
