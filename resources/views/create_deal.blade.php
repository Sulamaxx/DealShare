@extends('layouts.app')

@section('title', 'Forum')

@section('content')

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
                        <label for="inputTopicTitle">Deal Title</label>
                        <input type="text" name="title" class="form-control" id="inputTopicTitle"
                            placeholder="Subject of your topic" required>
                        <div class="tt-note">Describe your deal well, while keeping the subject short.</div>
                    </div>

                    <!-- Link -->
                    <div class="form-group">
                        <label for="inputDealLink">Deal Link</label>
                        <input type="text" name="link" class="form-control" id="inputDealLink"
                            placeholder="eg: https://example.com">
                    </div>

                    <!-- Discount Text -->
                    <div class="form-group">
                        <label for="inputDiscountText">Discount Text</label>
                        <input type="text" name="discount_text" class="form-control" id="inputDiscountText"
                            placeholder="eg: 20% OFF">
                    </div>

                    <!-- Price Saving -->
                    <div class="form-group">
                        <label for="inputPriceSaving">Price Saving</label>
                        <input type="text" name="price_saving" class="form-control" id="inputPriceSaving"
                            placeholder="eg: Save 800 LKR">
                    </div>

                    <!-- Deal Body -->
                    <div class="form-group">
                        <label for="inputDescription">Deal Description</label>
                        <textarea name="description" class="form-control" id="inputDescription" rows="5"
                            placeholder="Write details here..." required></textarea>
                    </div>

                    <!-- Category -->
                    <div class="form-group">
                        <label for="inputCategory">Category</label>
                        <select name="category" class="form-control" id="inputCategory" required>
                            <option value="">Select a Category</option>
                            <option value="Shopping Advice">Shopping Advice</option>
                            <option value="Product Reviews">Product Reviews</option>
                            <option value="Consumer Rights">Consumer Rights</option>
                            <!-- add more if you want -->
                        </select>
                    </div>

                    <!-- Image Upload -->
                    <div class="form-group">
                        <label for="inputImage">Upload Deal Image</label>
                        <input type="file" name="image" class="form-control-file" id="inputImage" accept="image/*">
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
