<div class="col-sm-6 col-md-6 col-lg-6">

    <div class="single-deal-card card mb-4"
        style="display: flex; flex-direction: row; border:none;border-radius:0%; overflow: hidden;">

        @if ($deal->image)
            <a href="{{ route('view-deal', $deal->id) }}" class="deal-image-area position-relative"
                style="flex-basis: 30%; background-image: url('{{ asset($deal->image) }}'); background-size: cover; background-position: center; background-repeat: no-repeat;"
                alt="{{ $deal->title }}">

            </a>
        @else
            <a class="deal-image-area position-relative"
                style="flex-basis: 30%; background-image: url('https://via.placeholder.com/300x150'); background-size: cover; background-position: center; background-repeat: no-repeat;"
                alt="Placeholder Image">

            </a>
        @endif

        <div class="deal-details-area p-1" style="flex-basis: 70%; display: flex; flex-direction: column;">


            <h5 style="font-size: 1.125em; margin-bottom: 3px;font-weight:bolder">{{ $deal->title }}</h5>


            <p style="font-size: 0.875em; color: #555; margin-bottom: 3px; flex-grow: 1; overflow-wrap: anywhere;">
                {{ $deal->description }}
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
                        <i class="far fa-comment ms-2 me-1" style="font-weight: 600;"></i> 5
                    </span>
                    <div class="d-flex flex-wrap gap-1">

                        <span class=" fw-bold" style="margin-right: 1.25px;font-color:#ddd">
                            {{ $deal->category }}
                        </span>
                        <span style="margin-right: 1.25px;font-color:#ddd">|</span>
                        <span class="fw-bold" style="margin-right: 1em;font-color:#ddd">
                            Top Deals
                        </span>
                    </div>
                </div>
                @if ($deal->verified_member)
                    {{-- Tags and Verified Member --}}
                    <div class="d-flex flex-wrap align-items-center gap-2" style="margin-top: 0.307rem">
                        <div class="verified-member d-flex align-items-center text-success">
                            <i class="fas fa-star me-1"
                                style="padding: 2px;background-color: #DC3545;color: white;"></i>
                            {{-- Star icon placeholder --}}
                            Verified Member
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </div>
</div>
