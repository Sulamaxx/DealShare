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
                            style="border-radius: 20px; margin-top: auto; width:15vw;min-width:15vw;">

                        <select name="category" class="form-select w-auto" style="border-radius: 20px; margin-top: auto;">

                            <option value="">All Categories</option>
                            <option value="Electronics">Electronics</option>
                            <option value="Clothing">Clothing</option>
                            <option value="Food">Food</option>

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

                        <a href="#" class="fs-6 animated-link" {{-- Added a class for easier targeting in CSS --}}
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

                        <a href="#" class="fs-6 animated-link" {{-- Added a class for easier targeting in CSS --}}
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

                        {{-- @for ($i = 0; $i < 6; $i++)
                    <!-- Repeat deal card -->
                    <div class="col-md-6">
                        <div class="deal-card p-3">
                            <div class="row">
                                <div class="col-4">
                                    <div class="row">
                                        <img src="https://via.placeholder.com/300x150" class="deal-image mb-2">
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="row">
                                        <div class="deal-title">Save 800 LKR</div>
                                        <div class="deal-description">20% Off On Total Bill Value Cremalato offers a truly
                                            indulgent
                                            experience that satisfies even the most discerning sweet tooth.</div>
                                        <div class="deal-meta mt-2">
                                            <span class="text-success">+10</span> | 10min | 5 Hotel Deals | Top Deals
                                            <div class="verified mt-1">Verified Member</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor --}}
                        @foreach ($new_deals as $deal)
                            <!-- Repeat deal card -->
                            @include('deal.deal_card', [$deal])
                        @endforeach


                    </div>
                </div>
            </section>

            {{-- <section class="popular-deals-section" style="margin-top: 20px;line-height: 17.5px;">
                <div class="container" style="padding-inline: 0px">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>


                            <h2 style="margin-bottom: 0; font-weight: bold; font-size: 1.5rem; display: inline-block;">
                                Popular Deals
                            </h2>

                            <div style="width: 62.5px; height: 3px; background-color: #DC3545; display: inline-block;">
                            </div>


                        </div>

                        <a href="#" class="fs-6 animated-link" {{-- Added a class for easier targeting in CSS
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
                        <div class="col-12 col-md-6">
                            @for ($i = 0; $i < 3; $i++)
                                <div class="col-sm-12 col-md-12 col-lg-12">

                                    <div class="single-deal-card card mb-4"
                                        style="display: flex; flex-direction: row; border:none;border-radius:0%; overflow: hidden;">


                                        <div class="deal-image-area position-relative"
                                            style="flex-basis: 30%; background-image: url('placeholder-deal-image.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">

                                        </div>


                                        <div class="deal-details-area p-1"
                                            style="flex-basis: 70%; display: flex; flex-direction: column;">


                                            <h5 style="font-size: 1.125em; margin-bottom: 3px;font-weight:bolder">Save 800
                                                LKR
                                            </h5>


                                            <p style="font-size: 0.875em; color: #555; margin-bottom: 3px; flex-grow: 1;">
                                                20% Off On Total Bill Value Cremalato offers a truly indulgent experience
                                                that
                                                satisfies even the most discerning sweet tooth. most discerning sweet tooth.
                                                most discerning sweet tooth.
                                            </p>


                                            <small class="text-muted mb-2">
                                                <i class="fas fa-info-circle me-1"></i>
                                                <a href="#" style="text-decoration: none; color: #555;">powertools
                                                    specialists.com.au</a>
                                            </small>


                                            <div class="deal-meta mt-auto">
                                                <div class="d-flex align-items-center justify-content-between mb-1">

                                                    <div class="d-flex align-items-center"
                                                        style="background-color: #ddd;padding-inline: 5px;border-radius:3.75px">

                                                        <span class=" fw-bold"
                                                            style="margin-right: 5px;font-weight: bold;font-color:#ddd">
                                                            +10
                                                        </span>
                                                        <span style="margin-right: 5px;font-color:#ddd">|</span>
                                                        <span class="fw-bold"
                                                            style="margin-right: 5px;font-weight: bold;font-color:#ddd">
                                                            -1
                                                        </span>
                                                    </div>

                                                    <span class="text-muted">
                                                        10min
                                                        <i class="far fa-comment ms-2 me-1" style="font-weight: 600;"></i> 5
                                                    </span>
                                                    <div class="d-flex flex-wrap gap-1">

                                                        <span class=" fw-bold" style="margin-right: 1.25px;font-color:#ddd">
                                                            5 Hotel Deals
                                                        </span>
                                                        <span style="margin-right: 1.25px;font-color:#ddd">|</span>
                                                        <span class="fw-bold" style="margin-right: 1em;font-color:#ddd">
                                                            Top Deals
                                                        </span>
                                                    </div>
                                                </div>

                                                {{-- Tags and Verified Member
                                                <div class="d-flex flex-wrap align-items-center gap-2"
                                                    style="margin-top: 0.307rem">
                                                    <div class="verified-member d-flex align-items-center text-success">
                                                        <i class="fas fa-star me-1"
                                                            style="padding: 2px;background-color: #DC3545;color: white;"></i>
                                                        {{-- Star icon placeholder
                                                        Verified Member
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                        <div class="col-12 col-md-6">
                            @for ($i = 0; $i < 6; $i++)
                                <div class="col-sm-12 col-md-12 col-lg-12">

                                    <div class="single-deal-card card mb-4"
                                        style="display: flex; flex-direction: row; border:none;border-radius:0%; overflow: hidden;">


                                        <div class="deal-image-area position-relative"
                                            style="flex-basis: 30%; background-image: url('placeholder-deal-image.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">

                                        </div>


                                        <div class="deal-details-area p-1"
                                            style="flex-basis: 70%; display: flex; flex-direction: column;">


                                            <h5 style="font-size: 1.125em; margin-bottom: 3px;font-weight:bolder">Save 800
                                                LKR
                                            </h5>


                                            <div class="deal-meta mt-auto">
                                                <div class="d-flex align-items-center justify-content-between mb-1">

                                                    <div class="d-flex align-items-center"
                                                        style="background-color: #ddd;padding-inline: 5px;border-radius:3.75px">

                                                        <span class=" fw-bold"
                                                            style="margin-right: 5px;font-weight: bold;font-color:#ddd">
                                                            +10
                                                        </span>
                                                        <span style="margin-right: 5px;font-color:#ddd">|</span>
                                                        <span class="fw-bold"
                                                            style="margin-right: 5px;font-weight: bold;font-color:#ddd">
                                                            -1
                                                        </span>
                                                    </div>

                                                    <span class="text-muted">
                                                        10min
                                                        <i class="far fa-comment ms-2 me-1" style="font-weight: 600;"></i> 5
                                                    </span>
                                                    <div class="d-flex flex-wrap gap-1">

                                                        <span class=" fw-bold" style="margin-right: 1.25px;font-color:#ddd">
                                                            5 Hotel Deals
                                                        </span>
                                                        <span style="margin-right: 1.25px;font-color:#ddd">|</span>
                                                        <span class="fw-bold" style="margin-right: 1em;font-color:#ddd">
                                                            Top Deals
                                                        </span>
                                                    </div>
                                                </div>

                                                {{-- Tags and Verified Member
                                                <div class="d-flex flex-wrap align-items-center gap-2"
                                                    style="margin-top: 0.307rem">
                                                    <div class="verified-member d-flex align-items-center text-success">
                                                        <i class="fas fa-star me-1"
                                                            style="padding: 2px;background-color: #DC3545;color: white;"></i>
                                                        {{-- Star icon placeholder
                                                        Verified Member
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </section> --}}


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

                        <a href="#" class="fs-6 animated-link"
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



        </div>
    </main>




@endsection
