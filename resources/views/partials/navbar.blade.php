    <!-- tt-mobile menu -->
    <nav class="panel-menu" id="mobile-menu" style="background-color: #fe9001">
        <ul>

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

    <header id="tt-header">
        <div class="container" style="background-color:#222222; height: 120px; display: flex; align-items: center; ">
            <div class="row tt-row no-gutters col-12 p-0 justify-content-between">
                <div class="col-auto">
                    <!-- toggle mobile menu -->
                    <a class="toggle-mobile-menu" href="#">
                        <svg class="tt-icon">
                            <use xlink:href="#icon-menu_icon"></use>
                        </svg>
                    </a>
                    <!-- /toggle mobile menu -->
                    <!-- logo -->
                    <div class="">
                        <a href="/"><img src="images/logo.png" alt=""></a>
                    </div>
                    <!-- /logo -->
                    <!-- desctop menu -->
                    <div class="tt-desktop-menu">
                        <nav>
                            <ul>
                                <li class="nav-item "><a class="nav-link text-white active" href="#">Popular
                                        Deals</a></li>
                                <li class="nav-item "><a class="nav-link text-white" href="#">New Deals</a></li>
                                <li class="nav-item "><a class="nav-link text-white" href="#">Highly Voted
                                        Deals</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="col-auto ">
                    <div class="tt-account-btn">
                        <a href="{{ route('login') }}" class="btn btn-primary">Log in</a>
                        <a href="{{ route('register') }}" class="btn btn-secondary">Sign up</a>
                        <a href="{{ route('register') }}" class="btn" style="background-color: #fe9001;">Post Your
                            Deal</a>
                    </div>
                </div>
            </div>
        </div>
    </header>
