<style>
    .tt-desktop-menu nav ul li a {
        position: relative;
        text-decoration: none;
        padding-bottom: 5px;
    }

    .tt-desktop-menu nav ul li a::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 32px;
        transform: translateX(-16px);
        width: 0;
        height: 2px;
        background-color: #fe9001;
        transition: width 0.3s ease-in-out;
    }

    .tt-desktop-menu nav ul li a:hover::after,
    .tt-desktop-menu nav ul li a.active::after {
        width: 50%;
    }

    @media (min-width:1024px) {
        .tt-desktop-menu nav ul li a::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 16px;
            transform: translateX(-8px);
            width: 0;
            height: 2px;
            background-color: #fe9001;
            transition: width 0.3s ease-in-out;
        }

        @media (min-width:1440px) {
            .tt-desktop-menu nav ul li a::after {
                content: "";
                position: absolute;
                bottom: 0;
                left: 32px;
                transform: translateX(-16px);
                width: 0;
                height: 2px;
                background-color: #fe9001;
                transition: width 0.3s ease-in-out;
            }
        }
</style>

<nav class="panel-menu d-none" id="mobile-menu" style="background-color: #fe9001">
    <ul>
        <li><a href="#">Popular Deals</a></li>
        <li><a href="#">New Deals</a></li>
        <li><a href="#">Highly Voted Deals</a></li>

        @if (Auth::guest())
            <li><a href="{{ route('login') }}">Log In</a></li>
            <li><a href="{{ route('register') }}">Sign Up</a></li>
        @else
            <li><span>{{ Auth::user()->name }}</span></li>
            <li><a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">Logout</a>
            </li>
            <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @endif
        <li><a href="{{ route('create-deals') }}">Post Your Deal</a></li>
    </ul>
    <div class="mm-navbtn-names">
        <div class="mm-closebtn">
            Close
            <div class="tt-icon">
                <svg>
                    <use xlink:href="#icon-cancel"></use>
                </svg>
            </div>
        </div>
        <div class="mm-backbtn">Back</div>
    </div>
</nav>

<header id="tt-header" style="background-color:#222222; padding: 10px 0;">
    <div class="container" style="padding-inline: 15px;">
        <div class="row tt-row no-gutters align-items-center justify-content-between">
            <div class="col-auto">
                <div class="tt-logo">
                    <a href="/"><img src="images/logo.png" alt=""></a>
                </div>
            </div>
            <div class="col-auto flex-grow-1 d-flex justify-content-center d-none d-lg-flex">
                <div class="tt-desktop-menu">
                    <nav>
                        <ul class="d-flex mb-0">
                            <li class="nav-item "><a class="nav-link text-white" href="#">Popular
                                    Deals</a></li>
                            <li class="nav-item "><a class="nav-link text-white" href="#">New Deals</a></li>
                            <li class="nav-item "><a class="nav-link text-white" href="#">Highly Voted
                                    Deals</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="col-auto d-flex align-items-center">

                <div class="tt-account-links d-none d-lg-flex align-items-center mr-3">
                    @if (Auth::guest())
                        <a href="{{ route('login') }}" class="text-white"
                            style="text-decoration: none; margin-right: 5px;font-weight: bold">Log In</a>
                        <span class="text-white" style="margin-right: 5px;">|</span>
                        <a href="{{ route('register') }}" class="text-white" style="text-decoration: none;font-weight: bold">Sign Up</a>
                    @else
                        <div>
                            <span class="text-white fw-bold" style="margin-right: 5px;font-weight: bold">
                                {{ Auth::user()->name }}
                            </span>
                            <span class="text-white" style="margin-right: 5px;">|</span>
                            <a href="{{ route('logout') }}" class="text-white" style="text-decoration: none;font-weight: bold"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        </div>
                    @endif
                </div>

                <a href="{{ route('create-deals') }}" class="btn btn-sm"
                    style="background-color: #fe9001; color: #222222; border: none;border-radius:30px;font-size: 0.8rem;font-weight: bold">Post
                    Your
                    Deal</a>

                <a class="toggle-mobile-menu d-lg-none ml-3" href="#">
                    <svg class="tt-icon">
                        <use xlink:href="#icon-menu_icon"></use>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</header>
