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


    <main id="tt-pageContent" class="p-0">
        <div class="container">

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
                    </div>
                    <div class="row">

                        @foreach ($new_deals as $deal)
                            <!-- Repeat deal card -->
                            @include('deal.deal_card', [$deal])
                        @endforeach
                        <br>
                        <!-- Pagination links -->
                        {{ $new_deals->links() }}

                    </div>
                </div>
            </section>

        </div>
    </main>
@endsection
