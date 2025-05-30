@extends('backend.layout.layout')
@php
    $title = 'View Profile';
    $subTitle = 'View Profile';
    $script = '<script>
        // ======================== Upload Image Start =====================
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $("#imagePreview").css("background-image", "url(" + e.target.result + ")");
                    $("#imagePreview").hide();
                    $("#imagePreview").fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#imageUpload").change(function() {
            readURL(this);
        });
        // ======================== Upload Image End =====================

        // ================== Password Show Hide Js Start ==========
        function initializePasswordToggle(toggleSelector) {
            $(toggleSelector).on("click", function() {
                $(this).toggleClass("ri-eye-off-line");
                var input = $($(this).attr("data-toggle"));
                if (input.attr("type") === "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
            });
        }
        // Call the function
        initializePasswordToggle(".toggle-password");
        // ========================= Password Show Hide Js End ===========================
    </script>';
@endphp

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-12">
            <div
                class="user-grid-card relative border border-neutral-200 dark:border-neutral-600 rounded-2xl overflow-hidden bg-white dark:bg-neutral-700 h-full">

                <div class="pb-6 ms-6 mb-6 me-6 mt-3">
                    <div class="text-center border-b border-neutral-200 dark:border-neutral-600">
                        {{-- <img src="{{ asset('assets/images/user-grid/user-grid-img14.png') }}" alt=""
                            class="border br-white border-width-2-px w-200-px h-[200px] rounded-full object-fit-cover mx-auto"> --}}
                        <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('assets/images/user.png') }}"
                            alt="Profile Image"
                            class="border br-white border-width-2-px w-200-px h-[200px] rounded-full object-fit-cover mx-auto">

                        <h6 class="mb-0 mt-4">{{ $user->name }}</h6>
                        <span class="text-secondary-light mb-4">{{ $user->email }}</span>
                    </div>
                    <div class="mt-6">
                        <h6 class="text-xl mb-4">Personal Info</h6>
                        <ul>
                            {{-- <li class="flex items-center gap-1 mb-3">
                                <span class="w-[30%] text-base font-semibold text-neutral-600 dark:text-neutral-200">Full Name</span>
                                <span class="w-[70%] text-secondary-light font-medium">: Will Jonto</span>
                            </li>
                            <li class="flex items-center gap-1 mb-3">
                                <span class="w-[30%] text-base font-semibold text-neutral-600 dark:text-neutral-200"> Email</span>
                                <span class="w-[70%] text-secondary-light font-medium">: willjontoax@gmail.com</span>
                            </li>
                            <li class="flex items-center gap-1 mb-3">
                                <span class="w-[30%] text-base font-semibold text-neutral-600 dark:text-neutral-200"> Phone Number</span>
                                <span class="w-[70%] text-secondary-light font-medium">: (1) 2536 2561 2365</span>
                            </li>
                            <li class="flex items-center gap-1 mb-3">
                                <span class="w-[30%] text-base font-semibold text-neutral-600 dark:text-neutral-200"> Department</span>
                                <span class="w-[70%] text-secondary-light font-medium">: Design</span>
                            </li>
                            <li class="flex items-center gap-1 mb-3">
                                <span class="w-[30%] text-base font-semibold text-neutral-600 dark:text-neutral-200"> Designation</span>
                                <span class="w-[70%] text-secondary-light font-medium">: UI UX Designer</span>
                            </li>
                            <li class="flex items-center gap-1 mb-3">
                                <span class="w-[30%] text-base font-semibold text-neutral-600 dark:text-neutral-200"> Languages</span>
                                <span class="w-[70%] text-secondary-light font-medium">: English</span>
                            </li>
                            <li class="flex items-center gap-1">
                                <span class="w-[30%] text-base font-semibold text-neutral-600 dark:text-neutral-200"> Bio</span>
                                <span class="w-[70%] text-secondary-light font-medium">: Lorem Ipsum is simply dummy text of the       printing and typesetting industry.</span>
                            </li> --}}
                            <li class="flex items-center gap-1 mb-3">
                                <span class="w-[30%] text-base font-semibold text-neutral-600 dark:text-neutral-200">Full
                                    Name</span>
                                <span class="w-[70%] text-secondary-light font-medium">: {{ $user->name }}</span>
                            </li>

                            <li class="flex items-center gap-1 mb-3">
                                <span
                                    class="w-[30%] text-base font-semibold text-neutral-600 dark:text-neutral-200">Email</span>
                                <span class="w-[70%] text-secondary-light font-medium">: {{ $user->email }}</span>
                            </li>

                            <li class="flex items-center gap-1 mb-3">
                                <span
                                    class="w-[30%] text-base font-semibold text-neutral-600 dark:text-neutral-200">Status</span>
                                <span class="w-[70%] text-secondary-light font-medium">
                                    :
                                    @if ($user->status == 1)
                                        <span class="text-green-600">Active</span>
                                    @else
                                        <span class="text-red-600">Inactive</span>
                                    @endif
                                </span>
                            </li>

                            <li class="flex items-center gap-1 mb-3">
                                <span class="w-[30%] text-base font-semibold text-neutral-600 dark:text-neutral-200">User
                                    Type</span>
                                <span class="w-[70%] text-secondary-light font-medium">:
                                    {{ ucfirst($user->user_type) }}</span>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
