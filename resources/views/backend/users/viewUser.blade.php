@extends('backend.layout.layout')
@php
    $title = 'View User';
    $subTitle = 'View User';
    $script = '<script>
        // ================== Image Upload Js Start ===========================
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
        // ================== Image Upload Js End ===========================
    </script>';
@endphp

@section('content')
    <div class="card h-full p-0 rounded-xl border-0 overflow-hidden">
        <div class="card-body p-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 justify-center">
                <div class="col-span-12 lg:col-span-10 xl:col-span-8 2xl:col-span-6 2xl:col-start-4">
                    <div class="card border border-neutral-200 dark:border-neutral-600">
                        <div class="card-body">
                            <h6 class="text-base text-neutral-600 dark:text-neutral-200 mb-4">Profile Image</h6>
                            <form action="{{ route('admin.updateProfile', $user->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <!-- Upload Image Start -->
                                <div class="mb-6 mt-4">
                                    <div class="avatar-upload">
                                        <div class="avatar-edit absolute bottom-0 end-0 me-6 mt-4 z-[1] cursor-pointer ">
                                            <input type='file' id="imageUpload" name="image" accept=".png, .jpg, .jpeg"
                                                hidden>
                                            <label for="imageUpload"
                                                class="w-8 h-8 flex justify-center items-center bg-primary-50 dark:bg-primary-600/25 text-primary-600 dark:text-primary-400 border border-primary-600 hover:bg-primary-100 text-lg rounded-full">
                                                <iconify-icon icon="solar:camera-outline" class="icon"></iconify-icon>
                                            </label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="imagePreview"
                                                style="background-image: url({{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('assets/images/user.png') }});">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Upload Image End -->


                                <!-- Name Input -->
                                <div class="mb-5">
                                    <label for="name"
                                        class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                                        Full Name <span class="text-danger-600">*</span></label>
                                    <input type="text" class="form-control rounded-lg" id="name" name="name"
                                        placeholder="Enter Full Name" value="{{ $user->name ?? '' }}" required>
                                </div>

                                <!-- Email Input -->
                                <div class="mb-5">
                                    <label for="email"
                                        class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                                        Email <span class="text-danger-600">*</span></label>
                                    <input type="text" class="form-control rounded-lg" id="email" name="email"
                                        placeholder="Enter email address" value="{{ $user->email ?? '' }}" required>
                                </div>

                                <div class="mb-5">
                                    <label for="user_type"
                                        class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                                        User Type <span class="text-danger-600">*</span>
                                    </label>
                                    <select class="form-control rounded-lg form-select" id="user_type" name="user_type"
                                        required>
                                        <option value="user"
                                            {{ old('user_type', $user->user_type ?? '') == 'user' ? 'selected' : '' }}>User
                                        </option>
                                        <option value="moderator"
                                            {{ old('user_type', $user->user_type ?? '') == 'moderator' ? 'selected' : '' }}>
                                            Moderator</option>
                                    </select>
                                    @error('user_type')
                                        <div class="text-danger-600 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="flex items-center justify-center gap-3">
                                    <button type="submit"
                                        class="btn btn-primary border border-primary-600 text-base px-14 py-3 rounded-lg">
                                        Update
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
