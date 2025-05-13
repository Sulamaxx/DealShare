@extends('layouts.app')

@section('title', 'Forum')

@section('content')
    <style>
        .navbar-custom {
            background-color: #111;
        }

        .navbar-brand span {
            color: #28a745;
            font-weight: bold;
        }

        .banner {
            background-color: #f03c02;
            color: white;
            padding: 50px 20px;
        }

        .banner .tag {
            background: yellow;
            color: red;
            font-weight: bold;
            padding: 10px;
            border-radius: 50%;
            font-size: 1.2rem;
            position: absolute;
            top: 30px;
            right: 30px;
        }

        .filters select,
        .filters button {
            margin: 10px 5px 0 0;
        }

        .deal-banner {
            max-width: 100%;
            border-radius: 15px;
        }

        /* Deals */
        .deal-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 20px;
            background: #fff;
            transition: box-shadow 0.2s;
        }

        .deal-card:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .deal-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .deal-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .deal-description {
            font-size: 14px;
            color: #666;
        }

        .deal-meta {
            font-size: 12px;
            color: #999;
        }

        .section-header {
            font-size: 24px;
            font-weight: 600;
            border-left: 4px solid #28a745;
            padding-left: 10px;
            margin: 30px 0 15px;
        }

        .verified {
            font-size: 12px;
            color: #28a745;
            font-weight: bold;
        }

        .animated-link svg {
            transition: transform 0.3s ease-in-out;
            /* Add transition for smooth animation */
        }

        .animated-link:hover svg {
            transform: translateX(3px);
            /* Move the icon 3 pixels to the right on hover */
        }

        /* Optional: Add a slightly larger movement for the second arrow for a staggered effect */
        .animated-link svg:last-child {
            transition: transform 0.3s ease-in-out;
            /* Ensure transition is also on the second svg */
        }

        .animated-link:hover svg:last-child {
            transform: translateX(3px);
            /* Move the second icon further to the right */
        }

        /* Optional: Change color on hover */
        .animated-link:hover {
            color: #888 !important;
            /* Example: Change text color on hover */
        }
    </style>

    <div class="banner position-relative col-12"
        style="background-color: #fe4c01; color: white; padding: 40px 0;background-image: url('https://i.ibb.co/DMWPR9v/deal-phone.png'); background-size: cover; background-position: center right; background-repeat: no-repeat; min-height: 300px;">

        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">

                    <h3 class="fw-bold" style="color: white; font-size: 1.5em; margin-bottom: 5px;">Welcome to</h3>
                    <h1 class="fw-bold display-5" style="color: white; font-size: 2.5em; margin-bottom: 10px;">Bargains Forum
                    </h1>
                    <p class="lead" style="color: white; font-size: 1.2em; margin-bottom: 20px;">Share deals, discover
                        hidden gems, and connect with savvy shoppers!</p>

                    <form class="filters d-flex flex-wrap gap-2" method="GET" action="{{ route('deals.index') }}">

                        <input type="text" name="search" placeholder="Search deals..." class="form-control"
                            style="border-radius: 20px; margin-top: auto; width:15vw;min-width:15vw;"
                            value="{{ request('search') }}">

                        <select name="category" class="form-select w-auto" style="border-radius: 20px; margin-top: auto;">

                            <option value="">All Categories</option>
                            <option value="Electronics" {{ request('category') == 'Electronics' ? 'selected' : '' }}>
                                Electronics</option>
                            <option value="Clothing" {{ request('category') == 'Clothing' ? 'selected' : '' }}>Clothing
                            </option>
                            <option value="Food" {{ request('category') == 'Food' ? 'selected' : '' }}>Food
                            </option>

                        </select>

                        <button type="submit" class="btn btn-dark"
                            style="border-radius: 20px; margin-top: auto;">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <main id="tt-pageContent" class="p-0" style="min-height:80vh">
        <div class="container">

            <section class="popular-deals-section" style="margin-top: 20px;line-height: 17.5px;">
                <div class="container" style="padding-inline: 0px">

                    <div class="row">

                        @foreach ($search_deals as $deal)
                            <!-- Repeat deal card -->
                            @include('deal.deal_card', [$deal])
                        @endforeach
                        <br>
                        <!-- Pagination links -->
                        {{ $search_deals->links() }}

                    </div>
                </div>
            </section>

        </div>
    </main>
@endsection
