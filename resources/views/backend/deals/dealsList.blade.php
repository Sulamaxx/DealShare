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
                        <span class="text-base font-medium text-secondary-light mb-0">Show</span>
                        <select
                            class="form-select form-select-sm w-auto dark:bg-neutral-600 dark:text-white border-neutral-200 dark:border-neutral-500 rounded-lg">
                            @for ($i = 1; $i <= 10; $i++)
                                <option>{{ $i }}</option>
                            @endfor
                        </select>
                        <form class="navbar-search">
                            <input type="text" class="bg-white dark:bg-neutral-700 h-10 w-auto" name="search"
                                placeholder="Search deals...">
                            <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                        </form>
                        <select
                            class="form-select form-select-sm w-auto dark:bg-neutral-600 dark:text-white border-neutral-200 dark:border-neutral-500 rounded-lg">
                            <option>Status</option>
                            <option>Active</option>
                            <option>Expired</option>
                        </select>
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
                                    <th scope="col">
                                        <div class="flex items-center gap-10">
                                            <div class="form-check style-check flex items-center">
                                                <input class="form-check-input rounded border input-form-dark"
                                                    type="checkbox" id="selectAll">
                                            </div>
                                            S.L
                                        </div>
                                    </th>
                                    <th scope="col">Posted On</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Expiry Date</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < 10; $i++)
                                    <tr>
                                        <td>
                                            <div class="flex items-center gap-10">
                                                <div class="form-check style-check flex items-center">
                                                    <input class="form-check-input rounded border border-neutral-400"
                                                        type="checkbox">
                                                </div>
                                                {{ $i + 1 }}
                                            </div>
                                        </td>
                                        <td>{{ now()->subDays($i)->format('d M Y') }}</td>
                                        <td>
                                            <div class="flex items-center">
                                                <img src="{{ asset('assets/images/deals/deal' . (($i % 3) + 1) . '.jpg') }}"
                                                    alt=""
                                                    class="w-10 h-10 rounded-full shrink-0 me-2 overflow-hidden">
                                                <div class="grow">
                                                    <span class="text-base mb-0 font-normal text-secondary-light">Hot Deal
                                                        #{{ $i + 1 }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Electronics</td>
                                        <td>$99.99</td>
                                        <td>{{ now()->addDays(7 - $i)->format('d M Y') }}</td>
                                        <td class="text-center">
                                            <span
                                                class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 border border-success-600 px-6 py-1.5 rounded font-medium text-sm">
                                                {{ $i % 2 === 0 ? 'Active' : 'Expired' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="flex items-center gap-3 justify-center">
                                                <button type="button"
                                                    class="bg-info-100 dark:bg-info-600/25 hover:bg-info-200 text-info-600 dark:text-info-400 font-medium w-10 h-10 flex justify-center items-center rounded-full">
                                                    <iconify-icon icon="majesticons:eye-line"
                                                        class="icon text-xl"></iconify-icon>
                                                </button>
                                                <button type="button"
                                                    class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 bg-hover-success-200 font-medium w-10 h-10 flex justify-center items-center rounded-full">
                                                    <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                                </button>
                                                <button type="button"
                                                    class="remove-item-btn bg-danger-100 dark:bg-danger-600/25 hover:bg-danger-200 text-danger-600 dark:text-danger-500 font-medium w-10 h-10 flex justify-center items-center rounded-full">
                                                    <iconify-icon icon="fluent:delete-24-regular"
                                                        class="menu-icon"></iconify-icon>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>

                    <div class="flex items-center justify-between flex-wrap gap-2 mt-6">
                        <span>Showing 1 to 10 of 50 deals</span>
                        <ul class="pagination flex flex-wrap items-center gap-2 justify-center">
                            <li class="page-item">
                                <a class="page-link bg-neutral-300 dark:bg-neutral-600 text-secondary-light font-semibold rounded-lg border-0 flex items-center justify-center h-8 w-8 text-base"
                                    href="#"><iconify-icon icon="ep:d-arrow-left"></iconify-icon></a>
                            </li>
                            <li class="page-item"><a
                                    class="page-link bg-primary-600 text-white rounded-lg h-8 w-8 flex items-center justify-center text-base"
                                    href="#">1</a></li>
                            <li class="page-item"><a
                                    class="page-link bg-neutral-300 dark:bg-neutral-600 text-secondary-light rounded-lg h-8 w-8 flex items-center justify-center"
                                    href="#">2</a></li>
                            <li class="page-item"><a
                                    class="page-link bg-neutral-300 dark:bg-neutral-600 text-secondary-light rounded-lg h-8 w-8 flex items-center justify-center"
                                    href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link bg-neutral-300 dark:bg-neutral-600 text-secondary-light font-semibold rounded-lg border-0 flex items-center justify-center h-8 w-8 text-base"
                                    href="#"> <iconify-icon icon="ep:d-arrow-right"></iconify-icon> </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
