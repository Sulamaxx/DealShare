<style>
    .nav-link {
        position: relative;
        padding-bottom: 5px;
    }

    .nav-link::after {
        content: '';
        position: absolute;
        left: 50%;
        bottom: 0;
        transform: translateX(-50%);
        width: 0;
        height: 2px;
        background: #fe9001;
        transition: width 0.3s ease;
    }

    .nav-link:hover::after,
    .nav-link.active::after {
        width: 50%;
    }

    @media(max-width:426px) {
        .nav-link::after {
            content: '';
            position: absolute;
            left: 10%;
            bottom: 0;
            transform: translateX(-12%);
            width: 0;
            height: 2px;
            background: #fe9001;
            transition: width 0.3s ease;
        }
    }
</style>

<header class="bg-dark py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Logo -->
            <a href="/" class="navbar-brand">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" height="60" width="120">
            </a>

            <!-- Desktop Menu -->
            <nav class="d-none d-lg-block">
                <ul class="nav">
                    <li class="nav-item"><a class="nav-link text-white" href="{{ route('popular-deals') }}">Popular
                            Deals</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="{{ route('new-deals') }}">New Deals</a>
                    </li>
                    <li class="nav-item"><a class="nav-link text-white" href="{{ route('highly-voted-deals') }}">Highly
                            Voted Deals</a></li>
                    @auth
                        <li class="nav-item"><a class="nav-link text-white" href="{{ route('my-deals') }}">My Deals</a></li>
                    @endauth
                    @auth
                        <li class="nav-item"><a class="nav-link text-white" href="{{ route('my-activities') }}">My
                                Activities</a></li>
                    @endauth
                </ul>
            </nav>

            <!-- Auth Links & Post Deal Button -->
            <div class="d-none d-lg-flex align-items-center">
                @guest
                    <a href="{{ route('login') }}" class="text-white fw-bold me-2 text-decoration-none">Log In</a>
                    <span class="text-white me-2">|</span>
                    <a href="{{ route('register') }}" class="text-white fw-bold text-decoration-none">Sign Up</a>
                @else
                    <span class="text-white fw-bold me-2">{{ Auth::user()->name }}</span>
                    <span class="text-white me-2">|</span>
                    <a href="{{ route('logout') }}" class="text-white fw-bold text-decoration-none"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                @endguest

                <a href="{{ route('create-deals') }}" class="btn btn-warning btn-sm ms-3 fw-bold"
                    style="border-radius: 30px; background-color: #fe9001;">Post Your Deal</a>
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="d-lg-none btn text-white" id="mobileMenuToggle">
                {{-- Menu Icon (initially visible) --}}
                <svg class="menu-icon" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
                {{-- Close Icon (initially hidden) --}}
                <svg class="close-icon d-none" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
    </div>
</header>

<!-- Mobile Menu -->
<div class="d-lg-none d-none" id="mobileMenu" style="background-color: #e1e1e1;" >
    <ul class="nav flex-column p-3">
        <li class="nav-item"><a class="nav-link text-dark" href="{{ route('popular-deals') }}">Popular Deals</a></li>
        <li class="nav-item"><a class="nav-link text-dark" href="{{ route('new-deals') }}">New Deals</a></li>
        <li class="nav-item"><a class="nav-link text-dark" href="{{ route('highly-voted-deals') }}">Highly Voted
                Deals</a></li>
        @auth
            <li class="nav-item"><a class="nav-link text-dark" href="{{ route('my-deals') }}">My Deals</a></li>
        @endauth
        @auth
            <li class="nav-item"><a class="nav-link text-dark" href="{{ route('my-activities') }}">My Activities</a>
            </li>
        @endauth
        @guest
            <li class="nav-item"><a class="nav-link text-dark" href="{{ route('login') }}">Log In</a></li>
            <li class="nav-item"><a class="nav-link text-dark" href="{{ route('register') }}">Sign Up</a></li>
        @else
            <li class="nav-item text-dark" style="padding-left: 1rem;">{{ Auth::user()->name }}</li>
            <li class="nav-item"><a class="nav-link text-dark" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">Logout</a>
            </li>
            <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        @endguest
        <li class="nav-item"><a class="nav-link btn btn-warning btn-sm ms-3 fw-bold text-dark mt-3"
                style="border-radius: 30px; background-color: #fe9001;" href="{{ route('create-deals') }}">Post Your
                Deal</a></li>
    </ul>
</div>

<script>
    document.getElementById('mobileMenuToggle').addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobileMenu');
        const menuIcon = this.querySelector('.menu-icon');
        const closeIcon = this.querySelector('.close-icon');

        // Toggle the visibility of the mobile menu
        mobileMenu.classList.toggle('d-none');

        // Toggle the visibility of the icons
        menuIcon.classList.toggle('d-none');
        closeIcon.classList.toggle('d-none');
    });
</script>
