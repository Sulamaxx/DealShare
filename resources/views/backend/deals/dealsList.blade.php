@extends('backend.layout.layout')
@php
    $title = 'Deals List';
    $subTitle = 'Deals List';
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
                        <form method="GET" action="{{ route('dealsList') }}" class="flex items-center flex-wrap gap-4 mb-6">
                            <div class="row flex flex-row gap-3">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="form-control w-48 bg-white dark:bg-neutral-700" placeholder="Search deals...">

                                <select name="status" class="form-select w-32 dark:bg-neutral-700 dark:text-white">
                                    <option value="">All Status</option>
                                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>

                                <button type="submit" class="btn btn-primary btn-sm">
                                    <iconify-icon icon="ion:search-outline"></iconify-icon> Filter
                                </button>

                                <a href="{{ route('dealsList') }}" class="btn btn-secondary btn-sm">
                                    <iconify-icon icon="ph:arrow-counter-clockwise"></iconify-icon> Reset
                                </a>
                            </div>
                        </form>
                    </div>
                    <a href="{{ route('addDeal') }}"
                        class="btn btn-primary text-sm btn-sm px-3 py-3 rounded-lg flex items-center gap-2">
                        <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                        Add New Deal
                    </a>
                </div>
                <div class="card-body p-6">
                    <div class="table-responsive scroll-sm">
                        <table class="table bordered-table sm-table mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Posted On</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Link</th>
                                    <th>Post By</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($deals as $index => $deal)
                                    <tr>
                                        <td>{{ $deals->firstItem() + $index }}</td>
                                        <td>{{ $deal->created_at->format('d M Y') }}</td>
                                        <td>{{ $deal->title }}</td>
                                        <td>{{ $deal->category ?? '-' }}</td>
                                        <td><a href="{{ $deal->link }}" target="_blank">{{ $deal->link }}</a></td>
                                        <td>{{ $deal->user->name ?? '-' }}</td>
                                        <td class="text-center">
                                            @if ($deal->status == 1)
                                                <span
                                                    class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 border border-success-600 px-6 py-1.5 rounded font-medium text-sm">Active</span>
                                            @else
                                                <span
                                                    class="bg-neutral-200 dark:bg-neutral-600 text-neutral-600 border border-neutral-400 px-6 py-1.5 rounded font-medium text-sm">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="flex items-center gap-3 justify-center">
                                                <form action="{{ route('deal.status', $deal->id) }}" method="POST"
                                                    class="inline-block">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="{{ $deal->status == 0 ? 'bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 hover:bg-success-200' : 'bg-dark-100 dark:bg-dark-600/25 text-dark-600 dark:text-dark-400 hover:bg-dark-200' }} font-medium w-10 h-10 flex justify-center items-center rounded-full">
                                                        <iconify-icon icon="mdi:toggle-switch"
                                                            class="menu-icon"></iconify-icon>
                                                    </button>
                                                </form>
                                                <form method="GET" action="{{ route('deal.profile', $deal->id) }}">
                                                    @csrf
                                                    <button type="submit"
                                                        class="bg-info-100 dark:bg-info-600/25 hover:bg-info-200 text-info-600 dark:text-info-400 font-medium w-10 h-10 flex justify-center items-center rounded-full">
                                                        <iconify-icon icon="majesticons:eye-line"
                                                            class="icon text-xl"></iconify-icon>
                                                    </button>
                                                </form>
                                                <button type="button"
                                                    class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 bg-hover-success-200 font-medium w-10 h-10 flex justify-center items-center rounded-full">
                                                    <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                                </button>
                                                {{-- <button type="button"
                                                    class="remove-item-btn bg-danger-100 dark:bg-danger-600/25 hover:bg-danger-200 text-danger-600 dark:text-danger-500 font-medium w-10 h-10 flex justify-center items-center rounded-full">
                                                    <iconify-icon icon="fluent:delete-24-regular"
                                                        class="menu-icon"></iconify-icon>
                                                </button> --}}
                                                <form action="{{ route('deal.delete', $deal->id) }}" method="POST"
                                                    class="inline-block"
                                                    onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
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
                                        <td colspan="8" class="text-center">No deals found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="flex items-center justify-between flex-wrap gap-2 mt-6">
                        {{-- <span class="text-sm">Showing {{ $deals->firstItem() }} to {{ $deals->lastItem() }} of
                            {{ $deals->total() }} deals</span> --}}
                        {{ $deals->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
