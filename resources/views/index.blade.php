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
    </style>

    <section class="banner position-relative">
        <div class="container">
            <div class="row align-items-center">
                <!-- Left Column -->
                <div class="col-md-6">
                    <h3 class="fw-bold">Welcome to</h3>
                    <h1 class="fw-bold display-5">Bargains Forum</h1>
                    <p class="lead">Share deals, discover hidden gems, and connect with savvy shoppers!</p>

                    <!-- Filter Section -->
                    <form class="filters d-flex flex-wrap">
                        <select class="form-select w-auto">
                            <option>Categories</option>
                            <option>Electronics</option>
                            <option>Clothing</option>
                            <option>Food</option>
                        </select>
                        <select class="form-select w-auto">
                            <option>Store</option>
                            <option>Amazon</option>
                            <option>eBay</option>
                        </select>
                        <select class="form-select w-auto">
                            <option>Price Range</option>
                            <option>Under $50</option>
                            <option>$50 - $100</option>
                            <option>Above $100</option>
                        </select>
                        <button class="btn btn-dark">Search</button>
                    </form>
                </div>

                <!-- Right Column -->
                <div class="col-md-6 text-center position-relative">
                    <img src="https://i.ibb.co/DMWPR9v/deal-phone.png" alt="Deal Promo" class="deal-banner mt-4">
                    <div class="tag">UP TO <br> 60% OFF</div>
                </div>
            </div>
        </div>
    </section>


    <main id="tt-pageContent" class="p-0">
        <div class="container">


            <!-- Section: Popular Deals -->
            <div class="section-header">Popular Deals <a href="#" class="float-end fs-6 text-success">View All
                    &raquo;</a></div>
            <div class="row">
                <!-- Repeat deal card -->
                <div class="col-md-6">
                    <div class="deal-card p-3">
                        <img src="https://via.placeholder.com/300x150" class="deal-image mb-2">
                        <div class="deal-title">Save 800 LKR</div>
                        <div class="deal-description">20% Off On Total Bill Value Cremalato offers a truly indulgent
                            experience that satisfies even the most discerning sweet tooth.</div>
                        <div class="deal-meta mt-2">
                            <span class="text-success">+10</span> | 10min | 5 Hotel Deals | Top Deals
                            <div class="verified mt-1">Verified Member</div>
                        </div>
                    </div>
                </div>
                <!-- Repeat as needed -->
            </div>

            <!-- Section: New Deals -->
            <div class="section-header">New Deals <a href="#" class="float-end fs-6 text-success">View All &raquo;</a>
            </div>
            <div class="row">
                <!-- Repeat deal card -->
                <div class="col-md-6">
                    <div class="deal-card p-3">
                        <img src="https://via.placeholder.com/300x150" class="deal-image mb-2">
                        <div class="deal-title">Save 800 LKR</div>
                        <div class="deal-description">20% Off On Total Bill Value Cremalato offers a truly indulgent
                            experience that satisfies even the most discerning sweet tooth.</div>
                        <div class="deal-meta mt-2">
                            <span class="text-success">+10</span> | 10min | 5 Hotel Deals | Top Deals
                            <div class="verified mt-1">Verified Member</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section: Highly Voted Deals -->
            <div class="section-header">Highly Voted Deals <a href="#" class="float-end fs-6 text-success">View All
                    &raquo;</a></div>
            <div class="row">
                <!-- Repeat deal card -->
                <div class="col-md-6">
                    <div class="deal-card p-3">
                        <img src="https://via.placeholder.com/300x150" class="deal-image mb-2">
                        <div class="deal-title">Save 800 LKR</div>
                        <div class="deal-description">20% Off On Total Bill Value Cremalato offers a truly indulgent
                            experience that satisfies even the most discerning sweet tooth.</div>
                        <div class="deal-meta mt-2">
                            <span class="text-success">+10</span> | 10min | 5 Hotel Deals | Top Deals
                            <div class="verified mt-1">Verified Member</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tt-topic-list">
                <div class="tt-list-header">
                    <div class="tt-col-topic">Topic</div>
                    <div class="tt-col-category">Category</div>
                    <div class="tt-col-value hide-mobile">Likes</div>
                    <div class="tt-col-value hide-mobile">Replies</div>
                    <div class="tt-col-value hide-mobile">Views</div>
                    <div class="tt-col-value">Activity</div>
                </div>
                <div class="tt-topic-alert tt-alert-default" role="alert">
                    <a href="#" target="_blank">4 new posts</a> are added recently, click here to load them.
                </div>
                <div class="tt-item tt-itemselect">
                    <div class="tt-col-avatar">
                        <svg class="tt-icon">
                            <use xlink:href="#icon-ava-k"></use>
                        </svg>
                    </div>
                    <div class="tt-col-description">
                        <h6 class="tt-title"><a href="page-single-topic.html">
                                <svg class="tt-icon">
                                    <use xlink:href="#icon-pinned"></use>
                                </svg>
                                Halloween Costume Contest 2018
                            </a></h6>
                        <div class="row align-items-center no-gutters">
                            <div class="col-11">
                                <ul class="tt-list-badge">
                                    <li class="show-mobile"><a href="#"><span
                                                class="tt-color01 tt-badge">politics</span></a></li>
                                    <li><a href="#"><span class="tt-badge">contests</span></a></li>
                                    <li><a href="#"><span class="tt-badge">authors</span></a></li>
                                </ul>
                            </div>
                            <div class="col-1 ml-auto show-mobile">
                                <div class="tt-value">1h</div>
                            </div>
                        </div>
                    </div>
                    <div class="tt-col-category"><span class="tt-color01 tt-badge">politics</span></div>
                    <div class="tt-col-value hide-mobile">985</div>
                    <div class="tt-col-value tt-color-select hide-mobile">502</div>
                    <div class="tt-col-value hide-mobile">15.1k</div>
                    <div class="tt-col-value hide-mobile">1h</div>
                </div>
                <div class="tt-item tt-itemselect">
                    <div class="tt-col-avatar">
                        <svg class="tt-icon">
                            <use xlink:href="#icon-ava-l"></use>
                        </svg>
                    </div>
                    <div class="tt-col-description">
                        <h6 class="tt-title"><a href="page-single-topic.html">
                                <svg class="tt-icon">
                                    <use xlink:href="#icon-locked"></use>
                                </svg>
                                We’re removing Envato Credits from Market
                            </a></h6>
                        <div class="row align-items-center no-gutters  hide-desktope">
                            <div class="col-11">
                                <ul class="tt-list-badge">
                                    <li class="show-mobile"><a href="#"><span
                                                class="tt-color02 tt-badge">video</span></a></li>
                                </ul>
                            </div>
                            <div class="col-1 ml-auto show-mobile">
                                <div class="tt-value">2h</div>
                            </div>
                        </div>
                    </div>
                    <div class="tt-col-category"><span class="tt-color02 tt-badge">video</span></div>
                    <div class="tt-col-value hide-mobile">584</div>
                    <div class="tt-col-value tt-color-select  hide-mobile">35</div>
                    <div class="tt-col-value hide-mobile">1.3k</div>
                    <div class="tt-col-value hide-mobile">2h</div>
                </div>
                <div class="tt-item tt-itemselect">
                    <div class="tt-col-avatar">
                        <svg class="tt-icon">
                            <use xlink:href="#icon-ava-d"></use>
                        </svg>
                    </div>
                    <div class="tt-col-description">
                        <h6 class="tt-title"><a href="page-single-topic.html">
                                Web Hosting Packages for ThemeForest WordPress
                            </a></h6>
                        <div class="row align-items-center no-gutters">
                            <div class="col-11">
                                <ul class="tt-list-badge">
                                    <li class="show-mobile"><a href="#"><span
                                                class="tt-color03 tt-badge">exchange</span></a></li>
                                    <li><a href="#"><span class="tt-badge">themeforest</span></a></li>
                                    <li><a href="#"><span class="tt-badge">elements</span></a></li>
                                </ul>
                            </div>
                            <div class="col-1 ml-auto show-mobile">
                                <div class="tt-value">2h</div>
                            </div>
                        </div>
                    </div>
                    <div class="tt-col-category"><span class="tt-color03 tt-badge">exchange</span></div>
                    <div class="tt-col-value  hide-mobile">401</div>
                    <div class="tt-col-value tt-color-select  hide-mobile">975</div>
                    <div class="tt-col-value  hide-mobile">12.6k</div>
                    <div class="tt-col-value hide-mobile">2h</div>
                </div>
                <div class="tt-item">
                    <div class="tt-col-avatar">
                        <svg class="tt-icon">
                            <use xlink:href="#icon-ava-c"></use>
                        </svg>
                    </div>
                    <div class="tt-col-description">
                        <h6 class="tt-title"><a href="page-single-topic.html">
                                Review Queue Changes for VideoHive & PhotoDune
                            </a></h6>
                        <div class="row align-items-center no-gutters">
                            <div class="col-11">
                                <ul class="tt-list-badge">
                                    <li class="show-mobile"><a href="#"><span
                                                class="tt-color04 tt-badge">pets</span></a></li>
                                    <li><a href="#"><span class="tt-badge">videohive</span></a></li>
                                    <li><a href="#"><span class="tt-badge">photodune</span></a></li>
                                </ul>
                            </div>
                            <div class="col-1 ml-auto show-mobile">
                                <div class="tt-value">1d</div>
                            </div>
                        </div>
                    </div>
                    <div class="tt-col-category"><span class="tt-color04 tt-badge">pets</span></div>
                    <div class="tt-col-value  hide-mobile">308</div>
                    <div class="tt-col-value tt-color-select  hide-mobile">660</div>
                    <div class="tt-col-value  hide-mobile">8.3k</div>
                    <div class="tt-col-value hide-mobile">1d</div>
                </div>
                <div class="tt-item">
                    <div class="tt-col-avatar">
                        <svg class="tt-icon">
                            <use xlink:href="#icon-ava-n"></use>
                        </svg>
                    </div>
                    <div class="tt-col-description">
                        <h6 class="tt-title"><a href="page-single-topic.html">
                                Does Envato act against the authors of Envato markets?
                            </a></h6>
                        <div class="row align-items-center no-gutters  hide-desktope">
                            <div class="col-11">
                                <ul class="tt-list-badge">
                                    <li class="show-mobile"><a href="#"><span
                                                class="tt-color05 tt-badge">music</span></a></li>
                                </ul>
                            </div>
                            <div class="col-1 ml-auto show-mobile">
                                <div class="tt-value">1d</div>
                            </div>
                        </div>
                    </div>
                    <div class="tt-col-category"><span class="tt-color05 tt-badge">music</span></div>
                    <div class="tt-col-value hide-mobile">358</div>
                    <div class="tt-col-value tt-color-select hide-mobile">68</div>
                    <div class="tt-col-value hide-mobile">8.3k</div>
                    <div class="tt-col-value hide-mobile">1d</div>
                </div>
                <div class="tt-item">
                    <div class="tt-col-avatar">
                        <svg class="tt-icon">
                            <use xlink:href="#icon-ava-h"></use>
                        </svg>
                    </div>
                    <div class="tt-col-description">
                        <h6 class="tt-title"><a href="page-single-topic.html">
                                <svg class="tt-icon">
                                    <use xlink:href="#icon-locked"></use>
                                </svg>
                                We Want to Hear From You! What Would You Like?
                            </a></h6>
                        <div class="row align-items-center no-gutters  hide-desktope">
                            <div class="col-11">
                                <ul class="tt-list-badge">
                                    <li class="show-mobile"><a href="#"><span
                                                class="tt-color06 tt-badge">movies</span></a></li>
                                </ul>
                            </div>
                            <div class="col-1 ml-auto show-mobile">
                                <div class="tt-value">2d</div>
                            </div>
                        </div>
                    </div>
                    <div class="tt-col-category"><span class="tt-color06 tt-badge">movies</span></div>
                    <div class="tt-col-value hide-mobile">671</div>
                    <div class="tt-col-value tt-color-select  hide-mobile">29</div>
                    <div class="tt-col-value hide-mobile">1.3k</div>
                    <div class="tt-col-value hide-mobile">2d</div>
                </div>
                <div class="tt-item tt-item-popup">
                    <div class="tt-col-avatar">
                        <svg class="tt-icon">
                            <use xlink:href="#icon-ava-f"></use>
                        </svg>
                    </div>
                    <div class="tt-col-message">
                        Looks like you are new here. Register for free, learn and contribute.
                    </div>
                    <div class="tt-col-btn">
                        <button type="button" class="btn btn-primary">Log in</button>
                        <button type="button" class="btn btn-secondary">Sign up</button>
                        <button type="button" class="btn-icon">
                            <svg class="tt-icon">
                                <use xlink:href="#icon-cancel"></use>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="tt-item">
                    <div class="tt-col-avatar">
                        <svg class="tt-icon">
                            <use xlink:href="#icon-ava-t"></use>
                        </svg>
                    </div>
                    <div class="tt-col-description">
                        <h6 class="tt-title"><a href="page-single-topic.html">
                                Cannot customize my intro
                            </a></h6>
                        <div class="row align-items-center no-gutters">
                            <div class="col-11">
                                <ul class="tt-list-badge">
                                    <li class="show-mobile"><a href="#"><span class="tt-color07 tt-badge">video
                                                games</span></a></li>
                                    <li><a href="#"><span class="tt-badge">videohive</span></a></li>
                                    <li><a href="#"><span class="tt-badge">photodune</span></a></li>
                                </ul>
                            </div>
                            <div class="col-1 ml-auto show-mobile">
                                <div class="tt-value">2d</div>
                            </div>
                        </div>
                    </div>
                    <div class="tt-col-category"><span class="tt-color07 tt-badge">video games</span></div>
                    <div class="tt-col-value hide-mobile">364</div>
                    <div class="tt-col-value tt-color-select  hide-mobile">36</div>
                    <div class="tt-col-value  hide-mobile">982</div>
                    <div class="tt-col-value hide-mobile">2d</div>
                </div>
                <div class="tt-item">
                    <div class="tt-col-avatar">
                        <svg class="tt-icon">
                            <use xlink:href="#icon-ava-k"></use>
                        </svg>
                    </div>
                    <div class="tt-col-description">
                        <h6 class="tt-title"><a href="page-single-topic.html">
                                <svg class="tt-icon">
                                    <use xlink:href="#icon-verified"></use>
                                </svg>
                                Microsoft Word and Power Point
                            </a></h6>
                        <div class="row align-items-center no-gutters hide-desktope">
                            <div class="col-11">
                                <ul class="tt-list-badge ">
                                    <li class="show-mobile"><a href="#"><span
                                                class="tt-color08 tt-badge">youtube</span></a></li>
                                </ul>
                            </div>
                            <div class="col-1 ml-auto show-mobile">
                                <div class="tt-value">3d</div>
                            </div>
                        </div>
                    </div>
                    <div class="tt-col-category"><span class="tt-color08 tt-badge">youtube</span></div>
                    <div class="tt-col-value  hide-mobile">698</div>
                    <div class="tt-col-value tt-color-select  hide-mobile">78</div>
                    <div class="tt-col-value  hide-mobile">2.1k</div>
                    <div class="tt-col-value hide-mobile">3d</div>
                </div>
                <div class="tt-item">
                    <div class="tt-col-avatar">
                        <svg class="tt-icon">
                            <use xlink:href="#icon-ava-v"></use>
                        </svg>
                    </div>
                    <div class="tt-col-description">
                        <h6 class="tt-title"><a href="page-single-topic.html">
                                First website template got rejected.
                            </a></h6>
                        <div class="row align-items-center no-gutters  hide-desktope">
                            <div class="col-11">
                                <ul class="tt-list-badge">
                                    <li class="show-mobile"><a href="#"><span
                                                class="tt-color09 tt-badge">social</span></a></li>
                                </ul>
                            </div>
                            <div class="col-1 ml-auto show-mobile">
                                <div class="tt-value">3d</div>
                            </div>
                        </div>
                    </div>
                    <div class="tt-col-category"><span class="tt-color09 tt-badge">social</span></div>
                    <div class="tt-col-value  hide-mobile">12</div>
                    <div class="tt-col-value tt-color-select  hide-mobile">3</div>
                    <div class="tt-col-value  hide-mobile">268</div>
                    <div class="tt-col-value hide-mobile">3d</div>
                </div>
                <div class="tt-item">
                    <div class="tt-col-avatar">
                        <svg class="tt-icon">
                            <use xlink:href="#icon-ava-k"></use>
                        </svg>
                    </div>
                    <div class="tt-col-description">
                        <h6 class="tt-title"><a href="page-single-topic.html">
                                <svg class="tt-icon">
                                    <use xlink:href="#icon-pinned"></use>
                                </svg>
                                Proform or looking for contacting billing department
                            </a></h6>
                        <div class="row align-items-center no-gutters">
                            <div class="col-11">
                                <ul class="tt-list-badge">
                                    <li class="show-mobile"><a href="#"><span
                                                class="tt-color10 tt-badge">science</span></a></li>
                                    <li><a href="#"><span class="tt-badge">contests</span></a></li>
                                    <li><a href="#"><span class="tt-badge">authors</span></a></li>
                                </ul>
                            </div>
                            <div class="col-1 ml-auto show-mobile">
                                <div class="tt-value">3d</div>
                            </div>
                        </div>
                    </div>
                    <div class="tt-col-category"><span class="tt-color10 tt-badge">science</span></div>
                    <div class="tt-col-value hide-mobile">274</div>
                    <div class="tt-col-value tt-color-select hide-mobile">114</div>
                    <div class="tt-col-value  hide-mobile">2.3k</div>
                    <div class="tt-col-value hide-mobile">3d</div>
                </div>
                <div class="tt-item">
                    <div class="tt-col-avatar">
                        <svg class="tt-icon">
                            <use xlink:href="#icon-ava-y"></use>
                        </svg>
                    </div>
                    <div class="tt-col-description">
                        <h6 class="tt-title"><a href="page-single-topic.html">
                                <svg class="tt-icon">
                                    <use xlink:href="#icon-locked"></use>
                                </svg>
                                Refund for wrongly purchase HTML template
                            </a></h6>
                        <div class="row align-items-center no-gutters hide-desktope">
                            <div class="col-11">
                                <ul class="tt-list-badge">
                                    <li class="show-mobile"><a href="#"><span
                                                class="tt-color11 tt-badge">entertainment</span></a></li>
                                </ul>
                            </div>
                            <div class="col-1 ml-auto show-mobile">
                                <div class="tt-value">3d</div>
                            </div>
                        </div>
                    </div>
                    <div class="tt-col-category"><span class="tt-color11 tt-badge">entertainment</span></div>
                    <div class="tt-col-value hide-mobile">657</div>
                    <div class="tt-col-value tt-color-select hide-mobile">177</div>
                    <div class="tt-col-value hide-mobile">2.6k</div>
                    <div class="tt-col-value hide-mobile">3d</div>
                </div>
                <div class="tt-item">
                    <div class="tt-col-avatar">
                        <svg class="tt-icon">
                            <use xlink:href="#icon-ava-s"></use>
                        </svg>
                    </div>
                    <div class="tt-col-description">
                        <h6 class="tt-title"><a href="page-single-topic.html">
                                Why all my affiliate balance is pending?
                            </a></h6>
                        <div class="row align-items-center no-gutters">
                            <div class="col-11">
                                <ul class="tt-list-badge">
                                    <li class="show-mobile"><a href="#"><span
                                                class="tt-color03 tt-badge">exchange</span></a></li>
                                    <li><a href="#"><span class="tt-badge">themeforest</span></a></li>
                                    <li><a href="#"><span class="tt-badge">elements</span></a></li>
                                </ul>
                            </div>
                            <div class="col-1 ml-auto show-mobile">
                                <div class="tt-value">4d</div>
                            </div>
                        </div>
                    </div>
                    <div class="tt-col-category"><span class="tt-color03 tt-badge">exchange</span></div>
                    <div class="tt-col-value hide-mobile">37</div>
                    <div class="tt-col-value tt-color-select hide-mobile">31</div>
                    <div class="tt-col-value hide-mobile">257</div>
                    <div class="tt-col-value hide-mobile">4d</div>
                </div>
                <div class="tt-item">
                    <div class="tt-col-avatar">
                        <svg class="tt-icon">
                            <use xlink:href="#icon-ava-l"></use>
                        </svg>
                    </div>
                    <div class="tt-col-description">
                        <h6 class="tt-title"><a href="page-single-topic.html">
                                Google snippets wordpress plugin
                            </a></h6>
                        <div class="row align-items-center no-gutters">
                            <div class="col-11">
                                <ul class="tt-list-badge">
                                    <li class="show-mobile"><a href="#"><span
                                                class="tt-color04 tt-badge">pets</span></a></li>
                                    <li><a href="#"><span class="tt-badge">videohive</span></a></li>
                                    <li><a href="#"><span class="tt-badge">photodune</span></a></li>
                                </ul>
                            </div>
                            <div class="col-1 ml-auto show-mobile">
                                <div class="tt-value">4d</div>
                            </div>
                        </div>
                    </div>
                    <div class="tt-col-category"><span class="tt-color04 tt-badge">pets</span></div>
                    <div class="tt-col-value hide-mobile">987</div>
                    <div class="tt-col-value tt-color-select hide-mobile">271</div>
                    <div class="tt-col-value hide-mobile">3.8k</div>
                    <div class="tt-col-value hide-mobile">4d</div>
                </div>
                <div class="tt-item">
                    <div class="tt-col-avatar">
                        <svg class="tt-icon">
                            <use xlink:href="#icon-ava-n"></use>
                        </svg>
                    </div>
                    <div class="tt-col-description">
                        <h6 class="tt-title"><a href="page-single-topic.html">
                                How to use Team Listing?
                            </a></h6>
                        <div class="row align-items-center no-gutters hide-desktope">
                            <div class="col-11">
                                <ul class="tt-list-badge">
                                    <li class="show-mobile"><a href="#"><span
                                                class="tt-color09 tt-badge">social</span></a></li>
                                </ul>
                            </div>
                            <div class="col-1 ml-auto show-mobile">
                                <div class="tt-value">5d</div>
                            </div>
                        </div>
                    </div>
                    <div class="tt-col-category"><span class="tt-color09 tt-badge">social</span></div>
                    <div class="tt-col-value hide-mobile">324</div>
                    <div class="tt-col-value tt-color-select hide-mobile">163</div>
                    <div class="tt-col-value hide-mobile">2.3k</div>
                    <div class="tt-col-value hide-mobile">5d</div>
                </div>
                <div class="tt-item">
                    <div class="tt-col-avatar">
                        <svg class="tt-icon">
                            <use xlink:href="#icon-ava-r"></use>
                        </svg>
                    </div>
                    <div class="tt-col-description">
                        <h6 class="tt-title"><a href="page-single-topic.html">
                                <svg class="tt-icon">
                                    <use xlink:href="#icon-locked"></use>
                                </svg>
                                Can’t change image on main page of Coaching Theme
                            </a></h6>
                        <div class="row align-items-center no-gutters hide-desktope">
                            <div class="col-11">
                                <ul class="tt-list-badge">
                                    <li class="show-mobile"><a href="#"><span
                                                class="tt-color02 tt-badge">video</span></a></li>
                                </ul>
                            </div>
                            <div class="col-1 ml-auto show-mobile">
                                <div class="tt-value">5d</div>
                            </div>
                        </div>
                    </div>
                    <div class="tt-col-category"><span class="tt-color02 tt-badge">video</span></div>
                    <div class="tt-col-value hide-mobile">879</div>
                    <div class="tt-col-value tt-color-select hide-mobile">237</div>
                    <div class="tt-col-value hide-mobile">4.5k</div>
                    <div class="tt-col-value hide-mobile">5d</div>
                </div>
                <div class="tt-item">
                    <div class="tt-col-avatar">
                        <svg class="tt-icon">
                            <use xlink:href="#icon-ava-b"></use>
                        </svg>
                    </div>
                    <div class="tt-col-description">
                        <h6 class="tt-title"><a href="page-single-topic.html">
                                Documentation on Glitch package usage?
                            </a></h6>
                        <div class="row align-items-center no-gutters">
                            <div class="col-11">
                                <ul class="tt-list-badge">
                                    <li class="show-mobile"><a href="#"><span
                                                class="tt-color12 tt-badge">arts</span></a></li>
                                    <li><a href="#"><span class="tt-badge">themeforest</span></a></li>
                                    <li><a href="#"><span class="tt-badge">elements</span></a></li>
                                </ul>
                            </div>
                            <div class="col-1 ml-auto show-mobile">
                                <div class="tt-value">5d</div>
                            </div>
                        </div>
                    </div>
                    <div class="tt-col-category"><span class="tt-color12 tt-badge">arts</span></div>
                    <div class="tt-col-value hide-mobile">726</div>
                    <div class="tt-col-value tt-color-select hide-mobile">246</div>
                    <div class="tt-col-value hide-mobile">7.6k</div>
                    <div class="tt-col-value hide-mobile">5d</div>
                </div>
                <div class="tt-item">
                    <div class="tt-col-avatar">
                        <svg class="tt-icon">
                            <use xlink:href="#icon-ava-a"></use>
                        </svg>
                    </div>
                    <div class="tt-col-description">
                        <h6 class="tt-title"><a href="page-single-topic.html">
                                Woohoo! You’ve made it. Welcome to the Elite Club
                            </a></h6>
                        <div class="row align-items-center no-gutters">
                            <div class="col-11">
                                <ul class="tt-list-badge">
                                    <li class="show-mobile"><a href="#"><span
                                                class="tt-color04 tt-badge">pets</span></a></li>
                                    <li><a href="#"><span class="tt-badge">videohive</span></a></li>
                                    <li><a href="#"><span class="tt-badge">photodune</span></a></li>
                                </ul>
                            </div>
                            <div class="col-1 ml-auto show-mobile">
                                <div class="tt-value">5d</div>
                            </div>
                        </div>
                    </div>
                    <div class="tt-col-category"><span class="tt-color04 tt-badge">pets</span></div>
                    <div class="tt-col-value hide-mobile">674</div>
                    <div class="tt-col-value tt-color-select hide-mobile">128</div>
                    <div class="tt-col-value hide-mobile">1.3k</div>
                    <div class="tt-col-value hide-mobile">5d</div>
                </div>
                <div class="tt-row-btn">
                    <button type="button" class="btn-icon js-topiclist-showmore">
                        <svg class="tt-icon">
                            <use xlink:href="#icon-load_lore_icon"></use>
                        </svg>
                    </button>
                </div>
            </div>

        </div>
    </main>

    <div id="js-popup-settings" class="tt-popup-settings">
        <div class="tt-btn-col-close">
            <a href="#">
                <span class="tt-icon-title">
                    <svg>
                        <use xlink:href="#icon-settings_fill"></use>
                    </svg>
                </span>
                <span class="tt-icon-text">
                    Settings
                </span>
                <span class="tt-icon-close">
                    <svg>
                        <use xlink:href="#icon-cancel"></use>
                    </svg>
                </span>
            </a>
        </div>
        <form class="form-default">
            <div class="tt-form-upload">
                <div class="row no-gutter">
                    <div class="col-auto">
                        <div class="tt-avatar">
                            <svg>
                                <use xlink:href="#icon-ava-d"></use>
                            </svg>
                        </div>
                    </div>
                    <div class="col-auto ml-auto">
                        <a href="#" class="btn btn-primary">Upload Picture</a>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="settingsUserName">Username</label>
                <input type="text" name="name" class="form-control" id="settingsUserName"
                    placeholder="azyrusmax">
            </div>
            <div class="form-group">
                <label for="settingsUserEmail">Email</label>
                <input type="text" name="name" class="form-control" id="settingsUserEmail"
                    placeholder="Sample@sample.com">
            </div>
            <div class="form-group">
                <label for="settingsUserPassword">Password</label>
                <input type="password" name="name" class="form-control" id="settingsUserPassword"
                    placeholder="************">
            </div>
            <div class="form-group">
                <label for="settingsUserLocation">Location</label>
                <input type="text" name="name" class="form-control" id="settingsUserLocation"
                    placeholder="Slovakia">
            </div>
            <div class="form-group">
                <label for="settingsUserWebsite">Website</label>
                <input type="text" name="name" class="form-control" id="settingsUserWebsite"
                    placeholder="Sample.com">
            </div>
            <div class="form-group">
                <label for="settingsUserAbout">About</label>
                <textarea name="" placeholder="Few words about you" class="form-control" id="settingsUserAbout"></textarea>
            </div>
            <div class="form-group">
                <label for="settingsUserAbout">Notify me via Email</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="settingsCheckBox01" name="checkbox">
                    <label for="settingsCheckBox01">
                        <span class="check"></span>
                        <span class="box"></span>
                        <span class="tt-text">When someone replies to my thread</span>
                    </label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="settingsCheckBox02" name="checkbox">
                    <label for="settingsCheckBox02">
                        <span class="check"></span>
                        <span class="box"></span>
                        <span class="tt-text">When someone likes my thread or reply</span>
                    </label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="settingsCheckBox03" name="checkbox">
                    <label for="settingsCheckBox03">
                        <span class="check"></span>
                        <span class="box"></span>
                        <span class="tt-text">When someone mentions me</span>
                    </label>
                </div>
            </div>
            <div class="form-group">
                <a href="#" class="btn btn-secondary">Save</a>
            </div>
        </form>
    </div>

    {{-- <a href="page-create-topic.html" class="tt-btn-create-topic">
        <span class="tt-icon">
            <svg>
                <use xlink:href="#icon-create_new"></use>
            </svg>
        </span>
    </a> --}}

    {{-- <div class="modal fade" id="modalAdvancedSearch" tabindex="-1" role="dialog" aria-label="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="tt-modal-advancedSearch">
                    <div class="tt-modal-title">
                        <i class="pt-icon">
                            <svg>
                                <use xlink:href="#icon-advanced_search"></use>
                            </svg>
                        </i>
                        Advanced Search
                        <a class="pt-close-modal" href="#" data-dismiss="modal">
                            <svg class="icon">
                                <use xlink:href="#icon-cancel"></use>
                            </svg>
                        </a>
                    </div>
                    <form class="form-default">
                        <div class="form-group">
                            <i class="pt-customInputIcon">
                                <svg class="tt-icon">
                                    <use xlink:href="#icon-search"></use>
                                </svg>
                            </i>
                            <input type="text" name="name" class="form-control pt-customInputSearch"
                                id="searchForum" placeholder="Search all forums">
                        </div>
                        <div class="form-group">
                            <label for="searchName">Posted by</label>
                            <input type="text" name="name" class="form-control" id="searchName"
                                placeholder="Username">
                        </div>
                        <div class="form-group">
                            <label for="searchCategory">In Category</label>
                            <input type="text" name="name" class="form-control" id="searchCategory"
                                placeholder="Add Category">
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="searcCheckBox01" name="checkbox">
                            <label for="searcCheckBox01">
                                <span class="check"></span>
                                <span class="box"></span>
                                <span class="tt-text">Include all tags</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label>Only return topics/posts that...</label>
                            <div class="checkbox-group">
                                <input type="checkbox" id="searcCheckBox02" name="checkbox">
                                <label for="searcCheckBox02">
                                    <span class="check"></span>
                                    <span class="box"></span>
                                    <span class="tt-text">I liked</span>
                                </label>
                            </div>
                            <div class="checkbox-group">
                                <input type="checkbox" id="searcCheckBox03" name="checkbox">
                                <label for="searcCheckBox03">
                                    <span class="check"></span>
                                    <span class="box"></span>
                                    <span class="tt-text">are in my messages</span>
                                </label>
                            </div>
                            <div class="checkbox-group">
                                <input type="checkbox" id="searcCheckBox04" name="checkbox">
                                <label for="searcCheckBox04">
                                    <span class="check"></span>
                                    <span class="box"></span>
                                    <span class="tt-text">I’ve read</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <select class="form-control" id="searchTop">
                                <option>any</option>
                                <option>value 01</option>
                                <option>value 02</option>
                                <option>value 03</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="searchaTopics">Where topics</label>
                            <select class="form-control" id="searchaTopics">
                                <option>any</option>
                                <option>value 01</option>
                                <option>value 02</option>
                                <option>value 03</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="searchAdvTime">Posted</label>
                            <div class="row">
                                <div class="col-6">
                                    <select class="form-control">
                                        <option>any</option>
                                        <option>value 01</option>
                                        <option>value 02</option>
                                        <option>value 03</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <input type="text" name="name" class="form-control" id="searchAdvTime"
                                        placeholder="dd-mm-yyyy">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="minPostCount">Minimum Post Count</label>
                            <select class="form-control" id="minPostCount">
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                                <option>6</option>
                                <option>7</option>
                                <option>8</option>
                                <option>9</option>
                                <option selected>10</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <a href="#" class="btn btn-secondary btn-block">Search</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}



@endsection
