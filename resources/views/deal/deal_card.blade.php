<div class="col-sm-6 col-md-6 col-lg-6">

    <div class="single-deal-card card mb-4 mobile-single-card"
        style="display: flex; flex-direction: row; border:none;border-radius:0%; overflow: hidden;min-height:175.1px;">

        @if ($deal->image)
            <a href="{{ route('view-deal', $deal->id) }}" class="deal-image-area position-relative mobile-image"
                style="flex-basis: 30%; background-image: url('{{ asset($deal->image) }}'); background-size: cover; background-position: center; background-repeat: no-repeat;min-width:30%"
                alt="{{ $deal->title }}">

            </a>
        @else
            <a class="deal-image-area position-relative"
                style="flex-basis: 30%; background-image: url('https://via.placeholder.com/300x150'); background-size: cover; background-position: center; background-repeat: no-repeat;min-width:30%"
                alt="Placeholder Image">

            </a>
        @endif

        <div class="deal-details-area p-1 card-mobile"
            style="flex-basis: 70%; display: flex; flex-direction: column;max-width:70%;">


            <h5 class="deal-title-clamp" style="font-size: 1.125em; margin-bottom: 3px;font-weight:bolder">
                <a href="{{ route('view-deal', $deal->id) }}" style="text-decoration: none; color: inherit;">
                    {{ $deal->title }}
                </a>
            </h5>

            <p class="deal-description-clamp" style="font-size: 0.875em; color: #555; margin-bottom: 3px; ">
                {{ $deal->description }}
            </p>


            <small class="text-muted mb-2 deal-info-line mt-auto">
                <i class="fas fa-info-circle me-1"></i>
                <a href="{{ $deal->link }}" target="_blank" class="deal-link-clamp"
                    style="text-decoration: none; color: #555;">{{ $deal->link }}</a>
            </small>


            <div class="deal-meta mt-auto">
                <div class="d-flex align-items-center justify-content-between mb-1">

                    <div class="d-flex align-items-center"
                        style="background-color: #ddd;padding-inline: 5px;border-radius:3.75px">

                        <span class=" fw-bold" style="margin-right: 5px;font-weight: bold;font-color:#ddd">
                            +{{ $deal->upvotes }}
                        </span>
                        <span style="margin-right: 5px;font-color:#ddd">|</span>
                        <span class="fw-bold" style="margin-right: 5px;font-weight: bold;font-color:#ddd">
                            -{{ $deal->downvotes }}
                        </span>
                    </div>

                    <span class="text-muted">
                        {{ $deal->created_at->diffForHumans() }}
                        <i class="far fa-comment ms-2 me-1" style="font-weight: 600;"></i> {{ $deal->comment_count }}
                    </span>
                    <div class="d-flex flex-wrap gap-1">

                        <span class=" fw-bold" style="margin-right: 1.25px;font-color:#ddd">
                            {{ $deal->category }}
                        </span>
                        {{-- <span style="margin-right: 1.25px;font-color:#ddd">|</span>
                        <span class="fw-bold" style="margin-right: 1em;font-color:#ddd">
                            Top Deals
                        </span> --}}
                    </div>
                </div>
                @if ($deal->author_badge)
                    {{-- Tags and Verified Member --}}
                    <div class="d-flex flex-wrap align-items-center gap-2" style="margin-top: 0.307rem">
                        <div class="verified-member d-flex align-items-center text-success">
                            <img class="me-1" style="width:20px;height:20px;"
                                src="{{ $deal->author_badge->icon }}"></i>
                            {{-- Star icon placeholder --}}
                            {{ $deal->author_badge->name }}
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </div>
</div>

<style>
    .deal-title-clamp {
        display: -webkit-box;
        /* Required for Webkit browsers for clamping */
        -webkit-line-clamp: 2;
        /* Limit to 2 lines (for truncation/ellipsis) */
        -webkit-box-orient: vertical;
        /* Arrange content vertically */
        overflow: hidden;
        /* Hide overflow */
        text-overflow: ellipsis;
        /* Add ellipsis (...) */
        white-space: normal;
        /* Allow text to wrap normally */
        /* max-width: 70%; */
        /* Keep or adjust max-width if needed based on layout */

        /* --- Properties to ensure space for 2 lines --- */
        line-height: 1.05em;
        /* Set a consistent line height */
        height: 2.1em;
        /* Set height to line-height * 2 */
        /* -------------------------------------------- */

        /* Fallback for overflow hiding (already there, keep) */
        /* overflow: hidden; */
    }

    .deal-description-clamp {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        /* Limit to 2 lines */
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        /* Add ellipsis */
        white-space: normal;
        /* Allow text to wrap within the clamped lines */
        line-height: 1.2em;
        /* Keep consistent line height */
        width: 100%;
        /* Ensure it takes full available width */
        min-height: 0;
    }

    .deal-title-clamp,
    .deal-description-clamp {
        /* Fallback for overflow hiding */
        overflow: hidden;
    }

    .deal-link-clamp {
        display: inline-block;
        max-width: 80%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .deal-info-line {
        display: flex;
        /* Enable flexbox for the container */
        align-items: center;
        /* Vertically align items to the center */
        /* gap: 5px; */
        /* Optional: Add gap between items if needed */
    }

    @media(max-width:1025px) {


        .deal-description-clamp {
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: normal;
            /* Add line-height and height here too if you want description to always have space for 3 lines */
            line-height: 1.35em !important;
            /* Use a line height appropriate for the description's font-size (0.875em) */
            height: 3.15em;
            /* Set height to line-height * 3 */
        }

    }

    @media(max-width:769px) {

        .card-mobile {
            max-width: 100% !important;
        }

        .mobile-single-card {
            flex-direction: column !important;
        }

        .mobile-image {
            min-height: 330px;
        }

        .deal-description-clamp {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: normal;
            /* Add line-height and height here too if you want description to always have space for 3 lines */
            line-height: 1.05em !important;
            /* Use a line height appropriate for the description's font-size (0.875em) */
            height: 3.15em;
            /* Set height to line-height * 3 */
        }

    }

    @media(max-width:426px) {

        .mobile-single-card {
            flex-direction: column !important;
        }

        .mobile-image {
            min-height: 415.6px;
        }

    }

    @media(max-width:376px) {

        .mobile-single-card {
            flex-direction: column !important;
        }

        .mobile-image {
            min-height: 365.2px;
        }

    }

    @media(max-width:321px) {

        .mobile-single-card {
            flex-direction: column !important;
        }

        .mobile-image {
            min-height: 310px;
        }

    }
</style>
