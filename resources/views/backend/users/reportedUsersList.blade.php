@extends('backend.layout.layout')
@php
    $title = 'Users List';
    $subTitle = 'Users List';
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
                        <form action="{{ route('reportedUsersList') }}" method="GET"
                            class="navbar-search flex items-center gap-2">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search"
                                class="bg-white dark:bg-neutral-700 h-10 w-auto">
                            <button type="submit" class="btn btn-primary">
                                <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon> Search
                            </button>

                            {{-- <a href="{{ route('reportedUsersList') }}"
                                class="btn btn-secondary bg-neutral-300 dark:bg-neutral-600 text-black dark:text-white">
                                <iconify-icon icon="material-symbols:restart-alt-rounded" class="icon"></iconify-icon>
                                Reset
                            </a> --}}
                            <a href="{{ route('reportedUsersList') }}" class="btn btn-secondary btn-sm">
                                <iconify-icon icon="ph:arrow-counter-clockwise" style="padding: 3px;"></iconify-icon> Reset
                            </a>
                        </form>

                    </div>
                </div>
                <div class="card-body p-6">
                    <div class="table-responsive scroll-sm">
                        <table class="table bordered-table sm-table mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">
                                        <div class="flex items-center gap-10">
                                            NO
                                        </div>
                                    </th>
                                    <th scope="col">Join Date</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col" class="text-center">Post Reports</th>
                                    <th scope="col" class="text-center">Comment reports</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $index => $user)
                                    <tr>
                                        <td>
                                            <div class="flex items-center gap-10">
                                                {{ $index + 1 }}
                                            </div>
                                        </td>
                                        <td>{{ Carbon\Carbon::parse($user->created_at)->format('Y-m-d') }}</td>
                                        <td>
                                            <div class="flex items-center">
                                                <img src="{{ $user->profile_photo_path != null ? asset('storage/' . $user->profile_photo_path) : asset('assets/images/user.png') }}"
                                                    alt=""
                                                    class="w-10 h-10 rounded-full shrink-0 me-2 overflow-hidden">
                                                <div class="grow">
                                                    <span
                                                        class="text-base mb-0 font-normal text-secondary-light">{{ $user->name }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span
                                                class="text-base mb-0 font-normal text-secondary-light">{{ $user->email }}</span>
                                        </td>
                                        <td><span
                                                class="text-base mb-0 font-normal text-secondary-light">{{ $user->post_report_count }}</span>
                                        </td>
                                        <td><span
                                                class="text-base mb-0 font-normal text-secondary-light">{{ $user->comment_report_count }}</span>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $statusClasses = [
                                                    1 => 'bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 border border-success-600',
                                                    0 => 'bg-neutral-200 dark:bg-neutral-600 text-neutral-600 border border-neutral-400',
                                                    2 => 'bg-warning-100 dark:bg-warning-600/25 text-warning-600 dark:text-warning-400 border border-warning-600',
                                                    3 => 'bg-danger-100 dark:bg-danger-600/25 text-danger-600 dark:text-danger-400 border border-danger-600',
                                                ];

                                                $statusLabels = [
                                                    1 => 'Active',
                                                    0 => 'Inactive',
                                                    2 => 'Temporarily Banned',
                                                    3 => 'Permanently Banned',
                                                ];
                                            @endphp

                                            <span
                                                class="{{ $statusClasses[$user->status] ?? 'bg-gray-200 text-gray-600 border border-gray-400' }} px-6 py-1.5 rounded font-medium text-sm">
                                                {{ $statusLabels[$user->status] ?? 'Unknown' }}
                                            </span>

                                        </td>

                                        <td class="text-center">
                                            <div class="flex items-center gap-3 justify-center">

                                                {{-- View Profile --}}
                                                <form method="GET" action="{{ route('user.profile', $user->id) }}">
                                                    @csrf
                                                    <button title="view profile" type="submit"
                                                        class="bg-info-100 dark:bg-info-600/25 hover:bg-info-200 text-info-600 dark:text-info-400 font-medium w-10 h-10 flex justify-center items-center rounded-full">
                                                        <iconify-icon icon="majesticons:eye-line"
                                                            class="icon text-xl"></iconify-icon>
                                                    </button>
                                                </form>

                                                {{-- Send Warning Email --}}
                                                <form method="POST" action="{{ route('user.warning.email', $user->id) }}"
                                                    class="inline-block">
                                                    @csrf
                                                    <button title="send warning email" type="submit"
                                                        class="bg-warning-100 dark:bg-warning-600/25 hover:bg-warning-200 text-warning-600 dark:text-warning-400 font-medium w-10 h-10 flex justify-center items-center rounded-full"
                                                        title="Send Warning Email">
                                                        <iconify-icon icon="mdi:email-alert-outline"
                                                            class="menu-icon"></iconify-icon>
                                                    </button>
                                                </form>

                                                {{-- Temporary Ban --}}
                                                <form method="POST" action="{{ route('user.ban.temp', $user->id) }}"
                                                    class="inline-block"
                                                    onsubmit="return confirm('Temporarily ban this user?');">
                                                    @csrf
                                                    <button title="temporary ban" type="submit"
                                                        class="bg-dark-100 dark:bg-dark-600/25 text-dark-600 dark:text-dark-400 hover:bg-dark-200 font-medium w-10 h-10 flex justify-center items-center rounded-full"
                                                        title="Temporary Ban">
                                                        <iconify-icon icon="mdi:account-clock-outline"
                                                            class="menu-icon"></iconify-icon>
                                                    </button>
                                                </form>

                                                {{-- Ban User --}}
                                                <form method="POST" action="{{ route('user.ban', $user->id) }}"
                                                    class="inline-block"
                                                    onsubmit="return confirm('Permanently ban this user?');">
                                                    @csrf
                                                    <button title="permanent ban" type="submit"
                                                        class="bg-danger-100 dark:bg-danger-600/25 hover:bg-danger-200 text-danger-600 dark:text-danger-500 font-medium w-10 h-10 flex justify-center items-center rounded-full"
                                                        title="Permanent Ban">
                                                        <iconify-icon icon="mdi:account-lock-outline"
                                                            class="menu-icon"></iconify-icon>
                                                    </button>
                                                </form>

                                            </div>
                                        </td>


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex items-center justify-between flex-wrap gap-2 mt-6">
                        {{ $users->appends(request()->query())->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
