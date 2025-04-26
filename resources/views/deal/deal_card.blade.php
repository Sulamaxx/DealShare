<div class="col-md-6">
                        <div class="deal-card p-3">
                            <div class="row">
                                <div class="col-4">
                                    <div class="row">
                                        @if ($deal->image)
                                            <img src="{{ asset('storage/deals/' . $deal->image) }}" class="deal-image mb-2"
                                                alt="{{ $deal->title }}">
                                        @else
                                            <img src="https://via.placeholder.com/300x150" class="deal-image mb-2"
                                                alt="Placeholder Image">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="row">
                                        <div class="deal-title">{{ $deal->title }}</div>
                                        <div class="deal-description"
                                        style="overflow-wrap: break-word">{{ $deal->description }}</div>
                                        <div class="deal-meta mt-2">
                                            <span class="text-success">+{{ $deal->upvotes - $deal->downvotes }}</span> |
                                            {{ $deal->created_at->diffForHumans() }} |
                                            <span>{{ $deal->category }}</span> |
                                            @if ($deal->verified_member)
                                                <span class="verified mt-1">Verified Member</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>