@extends('layouts.app')

@section('title', 'Buyme Bargians')

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
            position: relative;
            padding: 50px 20px;
            color: white;
            background-size: cover;
            background-position: center right;
            background-repeat: no-repeat;
            min-height: 400px;
            z-index: 1;
            overflow: hidden;
        }

        .banner::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            /* Dark overlay */
            z-index: -1;
        }

        .banner-bottom {
            position: relative;
            color: white;
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 200px;
            z-index: 1;
            overflow: hidden;
        }

        .banner-bottom::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* Dark overlay */
            z-index: -1;
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

        @media(max-width:1025px) {
            .search-width {
                min-width: 50vw !important;
            }
        }

        @media(max-width:426px) {
            .search-width {
                min-width: 90vw !important;
            }
        }
    </style>

    <div class="banner position-relative col-12"
        style="background-image: url({{ $banner['top_banner'] === 'images/banner.png' ? asset($banner['top_banner']) : asset('storage/' . $banner['top_banner']) }})">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">

                    <h3 class="fw-bold" style="color: white; font-size: 1.5em; margin-bottom: 5px;">Welcome to</h3>
                    <h1 class="fw-bold display-5" style="color: white; font-size: 2.5em; margin-bottom: 10px;">Buyme Bargians
                    </h1>
                    <p class="lead" style="color: white; font-size: 1.2em; margin-bottom: 20px;">Share deals, discover
                        hidden gems, and connect with savvy shoppers!</p>

                    <form class="filters d-flex flex-wrap gap-2" method="GET" action="{{ route('deals.index') }}">

                        <input type="text" name="search" placeholder="Search deals..." class="form-control search-width"
                            style="border-radius: 20px; margin-top: auto; width:15vw;min-width:21vw;">

                        <select name="category" class="form-select w-auto" style="border-radius: 20px; margin-top: auto;">

                            <option value="Deals & Coupons">Deals & Coupons</option>
                            <option value="Shopping Advice">Shopping Advice</option>
                            <option value="Product Reviews">Product Reviews</option>

                        </select>

                        <button type="submit" class="btn btn-dark"
                            style="border-radius: 20px; margin-top: auto;">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <main id="tt-pageContent" class="p-0">
        <div class="container">

            @if ($popular_deals->isNotEmpty())
                <section class="popular-deals-section" style="margin-top: 20px;line-height: 17.5px;">
                    <div class="container" style="padding-inline: 0px">

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>


                                <h2 style="margin-bottom: 0; font-weight: bold; font-size: 1.5rem; display: inline-block;">
                                    Popular Deals
                                </h2>

                                <div style="width: 62.5px; height: 3px; background-color: #DC3545; display: inline-block;">
                                </div>


                            </div>

                            <a href="{{ route('popular-deals') }}" class="fs-6 animated-link" {{-- Added a class for easier targeting in CSS --}}
                                style="text-decoration: none; color: #555; font-weight: bold; display: inline-flex; align-items: center;">
                                View All
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#28a745"
                                    viewBox="0 0 16 16" style="vertical-align: middle; margin-left: 0.125em;">
                                    <path fill-rule="evenodd"
                                        d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#28a745"
                                    viewBox="0 0 16 16" style="vertical-align: middle;margin-left: -0.5em;">
                                    <path fill-rule="evenodd"
                                        d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
                                </svg>
                            </a>
                        </div>


                        <div class="row">

                            @foreach ($popular_deals as $deal)
                                <!-- Repeat deal card -->
                                @include('deal.deal_card', [$deal])
                            @endforeach


                        </div>
                    </div>
                </section>
            @endif

            @if ($new_deals->isNotEmpty())
                <section class="popular-deals-section" style="margin-top: 20px;line-height: 17.5px;">
                    <div class="container" style="padding-inline: 0px">

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>


                                <h2 style="margin-bottom: 0; font-weight: bold; font-size: 1.5rem; display: inline-block;">
                                    New Deals
                                </h2>

                                <div style="width: 62.5px; height: 3px; background-color: #DC3545; display: inline-block;">
                                </div>


                            </div>

                            <a href="{{ route('new-deals') }}" class="fs-6 animated-link" {{-- Added a class for easier targeting in CSS --}}
                                style="text-decoration: none; color: #555; font-weight: bold; display: inline-flex; align-items: center;">
                                View All
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#28a745"
                                    viewBox="0 0 16 16" style="vertical-align: middle; margin-left: 0.125em;">
                                    <path fill-rule="evenodd"
                                        d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#28a745"
                                    viewBox="0 0 16 16" style="vertical-align: middle;margin-left: -0.5em;">
                                    <path fill-rule="evenodd"
                                        d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
                                </svg>
                            </a>
                        </div>
                        <div class="row">

                            @foreach ($new_deals as $deal)
                                <!-- Repeat deal card -->
                                @include('deal.deal_card', [$deal])
                            @endforeach


                        </div>
                    </div>
                </section>
            @endif

            @if ($highly_voted_deals->isNotEmpty())
                <section class="popular-deals-section" style="margin-top: 20px;line-height: 17.5px;">
                    <div class="container" style="padding-inline: 0px">

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>


                                <h2 style="margin-bottom: 0; font-weight: bold; font-size: 1.5rem; display: inline-block;">
                                    Highly Voted Deals
                                </h2>

                                <div style="width: 62.5px; height: 3px; background-color: #DC3545; display: inline-block;">
                                </div>


                            </div>

                            <a href="{{ route('highly-voted-deals') }}" class="fs-6 animated-link"
                                style="text-decoration: none; color: #555; font-weight: bold; display: inline-flex; align-items: center;">
                                View All
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#28a745"
                                    viewBox="0 0 16 16" style="vertical-align: middle; margin-left: 0.125em;">
                                    <path fill-rule="evenodd"
                                        d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#28a745"
                                    viewBox="0 0 16 16" style="vertical-align: middle;margin-left: -0.5em;">
                                    <path fill-rule="evenodd"
                                        d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
                                </svg>
                            </a>
                        </div>


                        <div class="row">

                            @foreach ($highly_voted_deals as $deal)
                                <!-- Repeat deal card -->
                                @include('deal.deal_card', [$deal])
                            @endforeach

                        </div>
                    </div>
                </section>
            @endif





        </div>
    </main>
    @if ($banner['bottom_banner'])
        <div class="banner-bottom position-relative col-12"
            style="max-width:1200px;justify-content:center;margin-inline:auto;;
            background-image: url({{ $banner['bottom_banner'] === 'images/banner.png' ? asset($banner['bottom_banner']) : asset('storage/' . $banner['bottom_banner']) }});">
        </div>
    @endif

@endsection
