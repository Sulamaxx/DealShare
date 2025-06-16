@extends('layouts.app')

@section('title', 'Buyme Bargians')

@section('content')

    <style>
        .custom-select {
            appearance: none !important;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url('/assets/icons/dropdown.svg') !important;
            background-repeat: no-repeat !important;
            background-position: right 0.75rem center !important;
            background-size: 2rem !important;
            padding-right: 2rem !important;
            cursor: pointer !important;
        }

        .form-control.custom-select {
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
        }

        .custom-select:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .required-asterisk {
            color: #dc3545; /* Red for visibility */
            font-weight: bold;
            margin-left: 0.25rem;
        }
    </style>


    <main id="tt-pageContent" class="p-5">
        <div class="container">
            <div class="tt-wrapper-inner mb-5">
                <h1 class="tt-title-border">
                    Create New Deal
                </h1>
                <form class="form-default form-create-topic" action="{{ route('posts.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <!-- Deal Title -->
                    <div class="form-group">
                        <label for="inputTopicTitle">Deal Title<span class="required-asterisk">*</span></label>
                        <input type="text" name="title" class="form-control" id="inputTopicTitle"
                            placeholder="Subject of your topic" required value="{{ old('title') }}">
                        <div class="tt-note">Describe your deal well, while keeping the subject short.</div>
                    </div>

                    <!-- Link -->
                    <div class="form-group">
                        <label for="inputDealLink">Deal Link</label>
                        <input type="text" name="link" class="form-control" id="inputDealLink"
                            placeholder="eg: https://example.com" value="{{ old('link') }}">
                    </div>

                    <!-- Discount Text -->
                    {{-- <div class="form-group">
                        <label for="inputDiscountText">Discount Text</label>
                        <input type="text" name="discount_text" class="form-control" id="inputDiscountText"
                            placeholder="eg: 20% OFF" value="{{ old('discount_text') }}">
                    </div> --}}

                    <!-- Price Saving -->
                    {{-- <div class="form-group">
                        <label for="inputPriceSaving">Price Saving</label>
                        <input type="text" name="price_saving" class="form-control" id="inputPriceSaving"
                            placeholder="eg: Save 800 LKR" value="{{ old('price_saving') }}">
                    </div> --}}

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="inputExpirationDate">Expiration Date</label>
                            <input type="date" name="expiration_date" class="form-control" id="inputExpirationDate"
                                value="{{ old('expiration_date') }}">
                            @error('expiration_date')
                                <div class="text-danger-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Store Input -->
                        <div class="form-group col-md-6">
                            <label for="inputStore">Store</label>
                            <input type="text" name="store" class="form-control" id="inputStore"
                                placeholder="e.g., Amazon, Walmart" value="{{ old('store') }}">
                            @error('store')
                                <div class="text-danger-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Deal Body -->
                    <div class="form-group">
                        <label for="inputDescription">Deal Description<span class="required-asterisk">*</span></label>
                        <textarea name="description" class="form-control" id="inputDescription" rows="5"
                            placeholder="Write details here..." required>{{ old('description') }}</textarea>
                    </div>

                    <!-- Category -->
                    <div class="form-group">
                        <label for="inputCategory">Category<span class="required-asterisk">*</span></label>
                        <select name="category" class="form-control custom-select" id="inputCategory" required>
                            {{-- <option value="">Select a Category</option> --}}
                            <option value="Deals & Coupons" {{ old('category') == 'Deals & Coupons' ? 'selected' : '' }}>
                                Deals & Coupons</option>
                            <option value="Shopping Advice" {{ old('category') == 'Shopping Advice' ? 'selected' : '' }}>
                                Shopping Advice</option>
                            <option value="Product Reviews" {{ old('category') == 'Product Reviews' ? 'selected' : '' }}>
                                Product Reviews</option>
                        </select>
                    </div>

                    <!-- Image Upload -->
                    <div class="form-group">
                        <label for="inputImage">Upload Deal Image</label>
                        <input type="file" name="image" class="form-control-file" id="inputImage" accept="image/*"
                            style="max-width: fit-content;">
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary btn-width-lg">Create Post</button>
                    </div>
                </form>

            </div>

        </div>
    </main>
@endsection
