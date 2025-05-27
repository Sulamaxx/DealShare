@extends('layouts.app')

@section('title', 'Buyme Bargians')

@section('content')

    @php

    @endphp

    <main id="tt-pageContent" class="p-5">
        <div class="container">
            <div class="tt-wrapper-inner mb-5">
                <h1 class="tt-title-border">
                    Edit Deal
                </h1>
                <form class="form-default form-create-topic" action="{{ route('posts.update') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    {{-- @method('PUT') --}}
                    <input type="hidden" name="id" value="{{ $post->id }}">

                    <!-- Deal Title -->
                    <div class="form-group">
                        <label for="inputTopicTitle">Deal Title</label>
                        <input type="text" name="title" class="form-control" id="inputTopicTitle"
                            value="{{ $post->title }}" required>
                        <div class="tt-note">Describe your deal well, while keeping the subject short.</div>
                    </div>

                    <!-- Link -->
                    <div class="form-group">
                        <label for="inputDealLink">Deal Link</label>
                        <input type="text" name="link" class="form-control" id="inputDealLink"
                            value="{{ $post->link }}">
                    </div>

                    <!-- Discount Text -->
                    <div class="form-group">
                        <label for="inputDiscountText">Discount Text</label>
                        <input type="text" name="discount_text" class="form-control" id="inputDiscountText"
                            value="{{ $post->discount_text }}">
                    </div>

                    <!-- Price Saving -->
                    <div class="form-group">
                        <label for="inputPriceSaving">Price Saving</label>
                        <input type="text" name="price_saving" class="form-control" id="inputPriceSaving"
                            value="{{ $post->price_saving }}">
                    </div>

                    {{-- --- New: Expiration Date Input --- --}}
                    <div class="form-group">
                        <label for="inputExpirationDate">Expiration Date</label>
                        <input type="date" name="expiration_date" class="form-control" id="inputExpirationDate"
                            value="{{ old('expiration_date', $post->expiration_date ? \Carbon\Carbon::parse($post->expiration_date)->format('Y-m-d') : '') }}">
                        @error('expiration_date')
                            <div class="text-danger-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- ---------------------------------- --}}

                    {{-- --- New: Store Input --- --}}
                    <div class="form-group">
                        <label for="inputStore">Store</label>
                        <input type="text" name="store" class="form-control" id="inputStore"
                            placeholder="e.g., Amazon, Walmart" value="{{ old('store', $post->store ?? '') }}">
                        @error('store')
                            <div class="text-danger-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Deal Body -->
                    <div class="form-group">
                        <label for="inputDescription">Deal Description</label>
                        <textarea name="description" class="form-control" id="inputDescription" rows="5" required>{{ $post->description }}</textarea>
                    </div>

                    <!-- Category -->
                    <div class="form-group">
                        <label for="inputCategory">Category</label>
                        <select name="category" class="form-control" id="inputCategory" required>
                            <option value="">Select a Category</option>
                            <option value="Shopping Advice" {{ $post->category == 'Shopping Advice' ? 'selected' : '' }}>
                                Shopping Advice</option>
                            <option value="Product Reviews" {{ $post->category == 'Product Reviews' ? 'selected' : '' }}>
                                Product Reviews</option>
                            <option value="Consumer Rights" {{ $post->category == 'Consumer Rights' ? 'selected' : '' }}>
                                Consumer Rights</option>
                            <!-- add more if you want -->
                        </select>
                    </div>

                    <!-- Existing Image Preview -->
                    <div class="mb-3">
                        <label>Current Image:</label><br>
                        <img id="previewImage" src="{{ asset($post->image) }}" alt="Current Deal Image"
                            class="img-thumbnail" style="max-width: 200px;">
                    </div>

                    <!-- Image Upload Input -->
                    <div class="form-group">
                        <label for="inputImage">Upload New Deal Image</label>
                        <input type="file" name="image" class="form-control-file" id="inputImage" accept="image/*">
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary btn-width-lg">Update Post</button>
                    </div>
                </form>

            </div>

        </div>
    </main>

    <script>
        document.getElementById('inputImage').addEventListener('change', function(event) {
            const [file] = event.target.files;
            if (file) {
                document.getElementById('previewImage').src = URL.createObjectURL(file);
            }
        });
    </script>

@endsection
