@php
    $user = Auth::user(); // Get the authenticated user
    if ($user) {
        $joinedDate = $user->created_at->format('F d, Y'); // e.g., "May 17, 2023"
        $totalDealsSubmitted = $user->posts()->count();
        $totalUpvotesReceived = $user->posts()->sum('upvotes');
        $totalDownvotesReceived = $user->posts()->sum('downvotes');
        $totalCommentsMade = $user->comments()->count();
    }
@endphp


<div id="js-popup-settings" class="tt-popup-settings" style="padding-top: 0px;">
    <div class="tt-btn-col-close">
        <a href="#" class="tt-popup-header-link">
            <span class="tt-icon-title" style="top:20px">
                <svg>
                    <use xlink:href="#icon-user"></use>
                </svg>
            </span>
            <span class="tt-icon-text">
                Profile Settings
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
                        <img id="avatarPreview"
                            src="{{ $user && Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset('assets/images/user.png') }}"
                            alt="{{ $user && Auth::user()->name ? Auth::user()->name : 'User' }}'s avatar"
                            style="width:50px;height:50px;">

                        {{-- @if (!Auth::user()->profile_photo_path)
                            <svg id="avatarSvgPlaceholder">
                                <use xlink:href="#icon-ava-d"></use>
                            </svg>
                        @endif --}}
                    </div>
                </div>
                <div class="col-auto ml-auto mt-2">
                    {{-- The file input is hidden, the label triggers it --}}
                    <input type='file' id="imageUpload" name="image" accept=".png, .jpg, .jpeg" hidden>
                    <label for="imageUpload" class="btn btn-primary">Upload Picture</label>
                </div>
            </div>
        </div>
        @error('image')
            <div class="text-danger">{{ $message }}</div>
        @enderror

        @if (isset($badge) && $badge)
            <div class="form-group">
                <label class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                    Badge
                </label>
                @if ($badge) {{-- Check if a badge was found --}}
                    <a href="#" title="{{ $badge->description }}" class="form-control-static">
                        <span class="tt-color-default tt-badge" style="padding-left: 0px; display: block;">
                            @if ($badge->icon)
                                {{-- Assuming badge->icon is the image path --}}
                                <img src="{{ asset($badge->icon) }}" alt="{{ $badge->name }}"
                                    style="max-width: 22.5px; height: auto; vertical-align: middle; margin-right: 5px;">
                            @else
                                {{-- Fallback if no image, maybe use name or text --}}
                                {{ $badge->name }}
                            @endif
                            {{-- ({{ $badge->vote_count }}) --}}
                        </span>
                    </a>
                @else
                    {{-- Show this if no qualifying badge was found --}}
                    <span class="tt-color-none tt-badge" style="color:black">No Badges Earned Yet</span>

                @endif
            </div>
        @endif
        <div class="form-group">
            <label class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                Joined Date
            </label>
            <p class="form-control-static">{{ $user && $joinedDate ? $joinedDate : '' }}</p> {{-- Display joined date --}}
        </div>

        <div class="form-group">
            <label class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                Total Deals Submitted
            </label>
            <p class="form-control-static">{{ $user && $totalDealsSubmitted ? $totalDealsSubmitted : '' }}</p>
            {{-- Display total deals --}}
        </div>

        <div class="form-group">
            <label class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                Total Upvotes Received
            </label>
            <p class="form-control-static">{{ $user && $totalUpvotesReceived ? $totalUpvotesReceived : '' }}</p>
            {{-- Display total upvotes --}}
        </div>

        <div class="form-group">
            <label class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                Total Downvotes Received
            </label>
            <p class="form-control-static">{{ $user && $totalDownvotesReceived ? $totalDownvotesReceived : '' }}
            </p>
            {{-- Display total downvotes --}}
        </div>

        <div class="form-group">
            <label class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">
                Total Comments Made
            </label>
            <p class="form-control-static">{{ $user && $totalCommentsMade ? $totalCommentsMade : '' }}</p>
            {{-- Display total comments --}}
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
                {{-- --- FIX: Hidden input for unchecked checkbox value --- --}}
                <input type="hidden" name="is_private" value="0">
                {{-- --------------------------------------------------- --}}
                <input type="checkbox" id="settingsCheckBox04" name="is_private" value="1" {{-- Added value="1" --}}
                    {{ old('is_private', $user->is_private ?? false) ? 'checked' : '' }}>
                <label for="settingsCheckBox04">
                    <span class="check"></span>
                    <span class="box"></span>
                    <span class="tt-text">Private Account</span>
                </label>
            </div>
            @error('is_private')
                <div class="text-danger">{{ $message }}</div>
            @enderror
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

    /* Style for the link within the popup header to align items vertically */
    .tt-popup-header-link {
        display: flex;
        /* Enable Flexbox */
        align-items: center;
        /* Vertically align items in the center */
        gap: 8px;
        /* Add some space between the items (adjust as needed) */
        text-decoration: none;
        /* Remove underline from the link */
        color: inherit;
        /* Inherit color from parent or set explicitly */
        /* You might need to adjust padding/margin on the spans or the link itself */
    }

    /* Optional: Adjust spacing for the close icon if it needs to be pushed to the right */
    .tt-popup-header-link .tt-icon-close {
        margin-left: auto;
        /* Pushes the close icon to the far right */
    }

    /* Basic styling for the icons and text if not already defined */
    .tt-icon-title svg,
    .tt-icon-close svg {
        width: 20px;
        /* Adjust icon size as needed */
        height: 20px;
        fill: currentColor;
        /* Use current text color for SVG fill */
    }

    .tt-icon-text {
        font-weight: bold;
        /* Example styling for the text */
        /* Add font-size, color etc. as per your design */
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
                <a href="{{ route('pages') }}?tab=about">About</a> |
                <a href="{{ route('pages') }}?tab=faq">FAQ</a> |
                <a href="{{ route('pages') }}?tab=guidlines">Guidelines</a> |
                <a href="{{ route('pages') }}?tab=term_and_services">Terms</a> |
                <a href="{{ route('pages') }}?tab=privacy">Privacy</a> |
                {{-- <a href="{{ route('pages') }}">Blog</a> | --}}
                <a href="{{ route('pages') }}?tab=contact">Contact Us</a>
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
