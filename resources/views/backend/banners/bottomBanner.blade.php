@extends('backend.layout.layout')
@php
    $title = 'Update Bottom Banner';
    $subTitle = 'Update Bottom Banner';
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
                            <h6 class="text-base text-neutral-600 dark:text-neutral-200 mb-4">Banner Image</h6>
                            <form action="{{ route('admin.updateBottomBanner') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="mb-6 mt-4">
                                    <div class="avatar-upload" style="max-width:100vw">
                                        <div class="avatar-edit absolute bottom-0 end-0 me-6 mt-4 z-[1] cursor-pointer ">
                                            <input type='file' id="imageUpload" name="image"
                                                accept=".png, .jpg, .jpeg, .gif" hidden>
                                            <label for="imageUpload"
                                                class="w-8 h-8 flex justify-center items-center bg-primary-50 dark:bg-primary-600/25 text-primary-600 dark:text-primary-400 border border-primary-600 hover:bg-primary-100 text-lg rounded-full">
                                                <iconify-icon icon="solar:camera-outline" class="icon"></iconify-icon>
                                            </label>
                                        </div>
                                        <div class="avatar-preview" style="min-height:200px;width:100%;border-radius:0px;">
                                            <div id="imagePreview"
                                                style="background-image: url({{ $banner['bottom_banner'] === 'images/banner.png' ? asset($banner['bottom_banner']) : asset('storage/' . $banner['bottom_banner']) }});border-radius:0px;background-size: contain;">

                                            </div>
                                        </div>
                                    </div>
                                    @error('image')
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

    <style>
        .avatar-edit {
            bottom: 10px;
            right: -10px;
        }
    </style>
@endsection
