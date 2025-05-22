@extends('backend.layout.layout')
@php
    $title = 'Update Deal';
    $subTitle = 'Update Deal';
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
                            <h6 class="text-base text-neutral-600 dark:text-neutral-200 mb-4">Deal Image</h6>
                            <form action="{{ route('admin.updateDeal', $deal->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="mb-6 mt-4">
                                    <div class="avatar-upload" style="max-width:100vw">
                                        <div class="avatar-edit absolute bottom-0 end-0 me-6 mt-4 z-[1] cursor-pointer ">
                                            <input type='file' id="imageUpload" name="image" accept=".png, .jpg, .jpeg"
                                                hidden>
                                            <label for="imageUpload"
                                                class="w-8 h-8 flex justify-center items-center bg-primary-50 dark:bg-primary-600/25 text-primary-600 dark:text-primary-400 border border-primary-600 hover:bg-primary-100 text-lg rounded-full">
                                                <iconify-icon icon="solar:camera-outline" class="icon"></iconify-icon>
                                            </label>
                                        </div>
                                        <div class="avatar-preview" style="height:50vh;width:100%;border-radius:0px;">
                                            <div id="imagePreview"
                                                style="background-image: url({{ $deal->image ? asset($deal->image) : asset('assets/images/placeholder.png') }});border-radius:0px;background-size: contain;">
                                                {{-- Corrected image source to $deal->image --}}
                                            </div>
                                        </div>
                                    </div>
                                    @error('image')
                                        <div class="text-danger-600 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-5">
                                    <label for="title"
                                        class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                                        Deal Title <span class="text-danger-600">*</span></label>
                                    <input type="text" class="form-control rounded-lg" id="title" name="title"
                                        placeholder="Enter Deal Title" value="{{ old('title', $deal->title ?? '') }}"
                                        required>
                                    @error('title')
                                        <div class="text-danger-600 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-5">
                                    <label for="description"
                                        class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                                        Deal Description <span class="text-danger-600">*</span></label>
                                    <textarea class="form-control rounded-lg" id="description" name="description" placeholder="Enter Deal Description"
                                        rows="5" required>{{ old('description', $deal->description ?? '') }}</textarea>
                                    @error('description')
                                        <div class="text-danger-600 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-5">
                                    <label for="link"
                                        class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                                        Deal Link</label>
                                    <input type="url" class="form-control rounded-lg" id="link" name="link"
                                        placeholder="Enter Deal Link (e.g., https://example.com/deal)"
                                        value="{{ old('link', $deal->link ?? '') }}">
                                    @error('link')
                                        <div class="text-danger-600 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-5">
                                    <label for="discount_text"
                                        class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                                        Discount Text</label>
                                    <input type="text" class="form-control rounded-lg" id="discount_text"
                                        name="discount_text" placeholder="e.g., 20% Off, BOGO Free"
                                        value="{{ old('discount_text', $deal->discount_text ?? '') }}">
                                    @error('discount_text')
                                        <div class="text-danger-600 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-5">
                                    <label for="price_saving"
                                        class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                                        Price Saving</label>
                                    <input type="text" class="form-control rounded-lg" id="price_saving"
                                        name="price_saving" placeholder="e.g., Save $50, Was $100 Now $70"
                                        value="{{ old('price_saving', $deal->price_saving ?? '') }}">
                                    @error('price_saving')
                                        <div class="text-danger-600 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- --- New: Expiration Date Input --- --}}
                                <div class="mb-5">
                                    <label for="expiration_date"
                                        class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                                        Expiration Date</label>
                                    <input type="date" class="form-control rounded-lg" id="expiration_date"
                                        name="expiration_date"
                                        value="{{ old('expiration_date', $deal->expiration_date ? \Carbon\Carbon::parse($deal->expiration_date)->format('Y-m-d') : '') }}">
                                    @error('expiration_date')
                                        <div class="text-danger-600 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                {{-- ---------------------------------- --}}

                                {{-- --- New: Store Input --- --}}
                                <div class="mb-5">
                                    <label for="store"
                                        class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                                        Store</label>
                                    <input type="text" class="form-control rounded-lg" id="store" name="store"
                                        placeholder="e.g., Amazon, Walmart" value="{{ old('store', $deal->store ?? '') }}">
                                    @error('store')
                                        <div class="text-danger-600 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                {{-- ------------------------ --}}

                                <div class="mb-5">
                                    <label for="category"
                                        class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                                        Category <span class="text-danger-600">*</span></label>
                                    <select class="form-control rounded-lg form-select" id="category" name="category"
                                        required>
                                        <option value="">Select Category</option>
                                        {{-- Example categories. You might load these dynamically from a database --}}
                                        @php
                                            $categories = ['Shopping Advice', 'Product Reviews', 'Consumer Rights'];
                                        @endphp
                                        @foreach ($categories as $category)
                                            <option value="{{ $category }}"
                                                {{ old('category', $deal->category ?? '') == $category ? 'selected' : '' }}>
                                                {{ $category }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category')
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
