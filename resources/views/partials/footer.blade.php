{{-- <!-- Remove the container if you want to extend the Footer to full width. -->
<div class="container-fluid p-0">
    <!-- Footer -->
    <footer class="text-center text-lg-start text-dark" style="background-color: #ECEFF1">
        <!-- Section: Social media -->
        <section class="d-flex justify-content-between p-4 text-white" style="background-color: #21D192">
            <!-- Left -->
            <div class="me-5">
                <span>Get connected with us on social networks:</span>
            </div>
            <!-- Left -->

            <!-- Right -->
            <div class="d-flex gap-4">
                <a href="" class="text-white me-4">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="" class="text-white me-4">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="" class="text-white me-4">
                    <i class="fab fa-google"></i>
                </a>
                <a href="" class="text-white me-4">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="" class="text-white me-4">
                    <i class="fab fa-linkedin"></i>
                </a>
                <a href="" class="text-white me-4">
                    <i class="fab fa-github"></i>
                </a>
            </div>
            <!-- Right -->
        </section>
        <!-- Section: Social media -->

        <!-- Section: Links  -->
        <section class="">
            <div class="container text-center text-md-start mt-5">
                <!-- Grid row -->
                <div class="row mt-3">
                    <!-- Grid column -->
                    <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
                        <!-- Content -->
                        <h6 class="text-uppercase fw-bold">Company name</h6>
                        <hr class="mb-4 mt-0 d-inline-block mx-auto"
                            style="width: 60px; background-color: #7c4dff; height: 2px" />
                        <p>
                            Here you can use rows and columns to organize your footer
                            content. Lorem ipsum dolor sit amet, consectetur adipisicing
                            elit.
                        </p>
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                        <!-- Links -->
                        <h6 class="text-uppercase fw-bold">Products</h6>
                        <hr class="mb-4 mt-0 d-inline-block mx-auto"
                            style="width: 60px; background-color: #7c4dff; height: 2px" />
                        <p>
                            <a href="#!" class="text-dark">MDBootstrap</a>
                        </p>
                        <p>
                            <a href="#!" class="text-dark">MDWordPress</a>
                        </p>
                        <p>
                            <a href="#!" class="text-dark">BrandFlow</a>
                        </p>
                        <p>
                            <a href="#!" class="text-dark">Bootstrap Angular</a>
                        </p>
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                        <!-- Links -->
                        <h6 class="text-uppercase fw-bold">Useful links</h6>
                        <hr class="mb-4 mt-0 d-inline-block mx-auto"
                            style="width: 60px; background-color: #7c4dff; height: 2px" />
                        <p>
                            <a href="#!" class="text-dark">Your Account</a>
                        </p>
                        <p>
                            <a href="#!" class="text-dark">Become an Affiliate</a>
                        </p>
                        <p>
                            <a href="#!" class="text-dark">Shipping Rates</a>
                        </p>
                        <p>
                            <a href="#!" class="text-dark">Help</a>
                        </p>
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                        <!-- Links -->
                        <h6 class="text-uppercase fw-bold">Contact</h6>
                        <hr class="mb-4 mt-0 d-inline-block mx-auto"
                            style="width: 60px; background-color: #7c4dff; height: 2px" />
                        <p><i class="fas fa-home mr-3"></i> New York, NY 10012, US</p>
                        <p><i class="fas fa-envelope mr-3"></i> info@example.com</p>
                        <p><i class="fas fa-phone mr-3"></i> + 01 234 567 88</p>
                        <p><i class="fas fa-print mr-3"></i> + 01 234 567 89</p>
                    </div>
                    <!-- Grid column -->
                </div>
                <!-- Grid row -->
            </div>
        </section>
        <!-- Section: Links  -->

        <!-- Copyright -->
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2)">
            © 2020 Copyright:
            <a class="text-dark" href="https://mdbootstrap.com/">MDBootstrap.com</a>
        </div>
        <!-- Copyright -->
    </footer>
    <!-- Footer -->
</div>
<!-- End of .container --> --}}

@php
    $user = Auth::user(); // Get the authenticated user

    $joinedDate = $user->created_at->format('F d, Y'); // e.g., "May 17, 2023"
    $totalDealsSubmitted = $user->posts()->count();
    $totalUpvotesReceived = $user->posts()->sum('upvotes');
    $totalDownvotesReceived = $user->posts()->sum('downvotes');
    $totalCommentsMade = $user->comments()->count();
@endphp

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
    <form class="form-default" action="{{ route('user.updateSettings') }}" method="POST" enctype="multipart/form-data">
        @csrf 
        @method('PUT')

        <div class="tt-form-upload">
            <div class="row no-gutter">
                <div class="col-auto">
                    <div class="tt-avatar">
                        {{-- --- Display user's avatar or a default image --- --}}
                        {{-- Always include an img tag with an ID for JS to target --}}
                        <img id="avatarPreview"
                            src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset('assets/images/user.png') }}"
                            alt="{{ Auth::user()->name }}'s avatar" style="width:40px;height:40px;">

                        {{-- @if (!Auth::user()->profile_photo_path)
                            <svg id="avatarSvgPlaceholder">
                                <use xlink:href="#icon-ava-d"></use>
                            </svg>
                        @endif --}}
                    </div>
                </div>
                <div class="col-auto ml-auto">
                    {{-- The file input is hidden, the label triggers it --}}
                    <input type='file' id="imageUpload" name="image" accept=".png, .jpg, .jpeg" hidden>
                    <label for="imageUpload" class="btn btn-primary">Upload Picture</label>
                </div>
            </div>
        </div>
        @error('image')
            <div class="text-danger">{{ $message }}</div>
        @enderror


        <div class="form-group">
            <label class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                Joined Date
            </label>
            <p class="form-control-static">{{ $joinedDate }}</p> {{-- Display joined date --}}
        </div>

        <div class="form-group">
            <label class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                Total Deals Submitted
            </label>
            <p class="form-control-static">{{ $totalDealsSubmitted }}</p> {{-- Display total deals --}}
        </div>

        <div class="form-group">
            <label class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                Total Upvotes Received
            </label>
            <p class="form-control-static">{{ $totalUpvotesReceived }}</p> {{-- Display total upvotes --}}
        </div>

        <div class="form-group">
            <label class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                Total Downvotes Received
            </label>
            <p class="form-control-static">{{ $totalDownvotesReceived }}</p> {{-- Display total downvotes --}}
        </div>

        <div class="form-group">
            <label class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                Total Comments Made
            </label>
            <p class="form-control-static">{{ $totalCommentsMade }}</p> {{-- Display total comments --}}
        </div>
        <div class="form-group">
            <label for="settingsUserName">Name</label>
            <input type="text" name="name" class="form-control" id="settingsUserName" placeholder="azyrusmax"
                value="{{ old('name', $user->name ?? '') }}">
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="settingsUserEmail">Email</label>
            <input type="text" name="email" class="form-control" id="settingsUserEmail"
                placeholder="Sample@sample.com" value="{{ old('email', $user->email ?? '') }}">
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <div class="checkbox-group">
                <input type="checkbox" id="settingsCheckBox04" name="is_private">
                <label for="settingsCheckBox04">
                    <span class="check"></span>
                    <span class="box"></span>
                    <span class="tt-text">Private Account</span>
                </label>
            </div>
        </div>
        <div class="form-group">
            <label for="settingsUserAbout">Notify me via Email</label>
            <div class="checkbox-group">
                <input type="checkbox" id="settingsCheckBox01" name="email_thread_reply">
                <label for="settingsCheckBox01">
                    <span class="check"></span>
                    <span class="box"></span>
                    <span class="tt-text">When someone replies to my thread</span>
                </label>
            </div>
            <div class="checkbox-group">
                <input type="checkbox" id="settingsCheckBox02" name="email_thread_reply_like">
                <label for="settingsCheckBox02">
                    <span class="check"></span>
                    <span class="box"></span>
                    <span class="tt-text">When someone likes my thread or reply</span>
                </label>
            </div>
            <div class="checkbox-group">
                <input type="checkbox" id="settingsCheckBox03" name="email_mention">
                <label for="settingsCheckBox03">
                    <span class="check"></span>
                    <span class="box"></span>
                    <span class="tt-text">When someone mentions me</span>
                </label>
            </div>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Save Settings</button>
        </div>
    </form>
</div>

<style>
    .footer {
        background-color: #e5e5e5;
        padding: 20px 0;
        font-family: 'Poppins', sans-serif;
    }

    .footer-container {
        max-width: 1200px;
        margin: auto;
        padding: 0 20px;
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        align-items: center;
    }

    .footer-left h2 {
        margin: 0;
        font-size: 24px;
    }

    .logo-bold {
        font-weight: 800;
        color: #009444;
    }

    .logo-orange {
        color: #f58220;
        font-weight: 600;
    }

    .footer-copy {
        margin-top: 5px;
        font-size: 14px;
        color: #555;
    }

    .footer-right {
        text-align: right;
    }

    .footer-links {
        margin-bottom: 10px;
        font-size: 14px;
    }

    .footer-links a {
        color: #333;
        text-decoration: none;
        margin: 0 5px;
    }

    .footer-social a {
        margin: 0 5px;
        font-size: 18px;
        color: #333;
        text-decoration: none;
    }

    .call-btn {
        background-color: #f58220;
        color: #fff;
        padding: 6px 12px;
        border-radius: 4px;
        font-weight: 600;
        text-decoration: none;
    }

    .call-btn i {
        margin-right: 5px;
    }

    @media (max-width: 768px) {
        .footer-container {
            flex-direction: column;
            text-align: center;
        }

        .footer-right {
            text-align: center;
            margin-top: 10px;
        }
    }
</style>

<footer class="footer" style="margin-top: 20px">
    <div class="footer-container">
        <div class="footer-left">
            <h2><span class="logo-bold">Buy</span><span class="logo-orange">Me</span></h2>
            <p class="footer-copy">© 2025 Buyme.lk. All Rights Reserved.</p>
        </div>
        <div class="footer-right">
            <div class="footer-links">
                <a href="#">Stay Safe</a> |
                <a href="#">FAQ</a> |
                <a href="#">Anti-Scam</a> |
                <a href="#">Terms</a> |
                <a href="#">Privacy</a> |
                <a href="#">Blog</a> |
                <a href="#">Contact Us</a>
            </div>
            <div class="footer-social">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="tel:0112356356" class="call-btn">
                    <i class="fab fa-whatsapp"></i> 0112 356 356
                </a>
            </div>
        </div>
    </div>
</footer>

<script>
    function readURL(input) {
        // Check if a file was selected
        if (input.files && input.files[0]) {
            var reader = new FileReader(); // Create a FileReader object

            // Define what happens when the file is successfully read
            reader.onload = function(e) {
                // Get the image element where the preview should be displayed
                var avatarImg = document.getElementById('avatarPreview');

                // Set the src attribute of the image to the data URL of the selected file
                avatarImg.src = e.target.result;

                // Ensure the image is displayed (in case the SVG was initially shown)
                avatarImg.style.display = 'block';

                // Hide the SVG placeholder if it exists
                var avatarSvg = document.getElementById('avatarSvgPlaceholder');
                if (avatarSvg) {
                    avatarSvg.style.display = 'none';
                }

                $(avatarImg).hide().fadeIn(650);
            }

            // Read the selected file as a data URL (base64 encoded string)
            reader.readAsDataURL(input.files[0]);
        } else {
            // Optional: Handle the case where the file input is cleared
            // Reset to the default image or show the SVG placeholder
            var avatarImg = document.getElementById('avatarPreview');
            var avatarSvg = document.getElementById('avatarSvgPlaceholder');

            // Set the image source back to the default
            avatarImg.src = "{{ asset('assets/images/user.png') }}";

            // If the SVG placeholder exists, hide the image and show the SVG
            if (avatarSvg) {
                avatarImg.style.display = 'none';
                avatarSvg.style.display = 'block';
            }
        }
    }

    // Attach the change event listener to the hidden file input
    // When a file is selected in the input with id="imageUpload", call the readURL function
    document.getElementById('imageUpload').addEventListener('change', function() {
        readURL(this);
    });

    // If you are using jQuery and the #imageUpload element is loaded via AJAX or dynamically:
    // $(document).on('change', '#imageUpload', function() {
    //     readURL(this);
    // });
</script>
