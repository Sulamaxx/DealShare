@extends('backend.layout.layout')
@php
    $title = 'Badges List';
    $subTitle = 'Badges List';
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
                        <form method="GET" action="{{ route('getBadges') }}" class="flex items-center flex-wrap gap-4 mb-6">
                            <div class="row flex flex-row gap-3">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="form-control w-48 bg-white dark:bg-neutral-700" placeholder="Search badges...">

                                <button type="submit" class="btn btn-primary btn-sm">
                                    <iconify-icon icon="ion:search-outline"></iconify-icon> Filter
                                </button>

                                <a href="{{ route('getBadges') }}" class="btn btn-secondary btn-sm">
                                    <iconify-icon icon="ph:arrow-counter-clockwise"></iconify-icon> Reset
                                </a>
                            </div>
                        </form>
                    </div>
                    <a href="{{ route('addBadge') }}"
                        class="btn btn-primary text-sm btn-sm px-3 py-3 rounded-lg flex items-center gap-2">
                        <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                        Add New Badge
                    </a>
                </div>
                <div class="card-body p-6">
                    <div class="table-responsive scroll-sm">
                        <table class="table bordered-table sm-table mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Created At</th>
                                    <th style="padding-left: 3rem;">Name</th>
                                    <th>Description</th>
                                    <th>Vote Count</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($badges as $index => $badge)
                                    <tr>
                                        <td>{{ $badges->firstItem() + $index }}</td>
                                        <td>{{ $badge->created_at->format('d M Y') }}</td>

                                        <td>
                                            <div class="flex items-center">
                                                <img src="{{ $badge->icon != null ? asset($badge->icon) : asset('assets/images/badge.png') }}"
                                                    alt=""
                                                    class="w-10 h-10 rounded-full shrink-0 me-2 overflow-hidden">
                                                <div class="grow">
                                                    <span
                                                        class="text-base mb-0 font-normal text-secondary-light">{{ $badge->name }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $badge->description ?? '-' }}</td>
                                        <td>{{ $badge->vote_count }}</td>

                                        <td class="text-center">
                                            <div class="flex items-center gap-3 justify-center">

                                                <form method="GET" action="{{ route('updateBadge', $badge->id) }}">
                                                    @csrf
                                                    <button title="update badge" type="submit"
                                                        class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 bg-hover-success-200 font-medium w-10 h-10 flex justify-center items-center rounded-full">
                                                        <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                                    </button>
                                                </form>

                                                {{-- <button type="button"
                                                    class="remove-item-btn bg-danger-100 dark:bg-danger-600/25 hover:bg-danger-200 text-danger-600 dark:text-danger-500 font-medium w-10 h-10 flex justify-center items-center rounded-full">
                                                    <iconify-icon icon="fluent:delete-24-regular"
                                                        class="menu-icon"></iconify-icon>
                                                </button> --}}
                                                <form action="{{ route('badge.delete', $badge->id) }}" method="POST"
                                                    class="inline-block"
                                                    onsubmit="return confirm('Are you sure you want to delete this badge?');">
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
                                        <td colspan="8" class="text-center">No badges found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="flex items-center justify-between flex-wrap gap-2 mt-6">
                        {{-- <span class="text-sm">Showing {{ $deals->firstItem() }} to {{ $deals->lastItem() }} of
                            {{ $deals->total() }} deals</span> --}}
                        {{ $badges->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
