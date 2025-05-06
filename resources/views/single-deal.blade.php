@extends('layouts.app')

@section('title', 'Forum')

@section('content')
    <main id="tt-pageContent">
        <div class="container">
            <div class="tt-single-topic-list">
                <div class="tt-item">
                    <div class="tt-single-topic">
                        <div class="tt-item-header">
                            <div class="tt-item-info info-top">
                                <div class="tt-avatar-icon">
                                    <img src="{{ asset($post->user->profile_photo_path) }}" alt="">
                                </div>
                                <div class="tt-avatar-title">
                                    <a href="javascript:void(0)">{{ $post->user->name }}</a>
                                </div>
                                <a href="javascript:void(0)" class="tt-info-time">
                                    <i class="tt-icon"><svg>
                                            <use xlink:href="#icon-time"></use>
                                        </svg></i>{{ $post->created_at }}
                                </a>
                            </div>
                            <h3 class="tt-item-title">
                                <a href="javascript:void(0)">{{ $post->title }}</a>
                            </h3>
                            <img class="mt-3" style="width: 100%;height: 400px;" src="{{ asset($post->image) }}"
                                alt="">
                        </div>
                        <div class="tt-item-description">
                            <p>
                                {{ $post->description }}
                            </p>

                            <p>
                                Link : <a href="{{ $post->link }}">{{ $post->link }}</a>
                            </p>
                        </div>
                        <div class="tt-item-info info-bottom">
                            <a  class="tt-icon-btn like-button" data-post-id="{{ $post->id }}" data-vote-type="up" onclick="vote(this)">
                                <i class="tt-icon"><svg>
                                        <use xlink:href="#icon-like"></use>
                                    </svg></i>
                                <span class="tt-text" id="up_vote_span">{{ $post->upvotes }}</span>
                            </a>
                            <a  class="tt-icon-btn dislike-button" data-post-id="{{ $post->id }}" data-vote-type="down" onclick="vote(this)">
                                <i class="tt-icon"><svg>
                                        <use xlink:href="#icon-dislike"></use>
                                    </svg></i>
                                <span class="tt-text" id="down_vote_span">{{ $post->downvotes }}</span>
                            </a>
                            <!-- <a href="#" class="tt-icon-btn">
                                <i class="tt-icon"><svg>
                                        <use xlink:href="#icon-favorite"></use>
                                    </svg></i>
                                <span class="tt-text">{{ $post->comment_count }}</span>
                            </a> -->
                            <div class="col-separator"></div>
                        </div>
                    </div>
                </div>

                <div class="tt-item">
                    <div class="tt-single-topic">
                        <div class="tt-item-header pt-noborder">
                            <div class="tt-item-info info-top">
                                <div class="tt-avatar-icon">
                                    <i class="tt-icon"><svg>
                                            <use xlink:href="#icon-ava-v"></use>
                                        </svg></i>
                                </div>
                                <div class="tt-avatar-title">
                                    <a href="#">vickey03</a>
                                </div>
                                <a href="#" class="tt-info-time">
                                    <i class="tt-icon"><svg>
                                            <use xlink:href="#icon-time"></use>
                                        </svg></i>6 Jan,2019
                                </a>
                            </div>
                        </div>
                        <div class="tt-item-description">
                            Finally!<br>
                            Are there any special recommendations for design or an updated guide that includes new preview
                            sizes, including retina displays?
                            <div class="topic-inner-list">
                                <div class="topic-inner">
                                    <div class="topic-inner-title">
                                        <div class="topic-inner-avatar">
                                            <i class="tt-icon"><svg>
                                                    <use xlink:href="#icon-ava-s"></use>
                                                </svg></i>
                                        </div>
                                        <div class="topic-inner-title"><a href="#">summit92</a></div>
                                    </div>
                                    <div class="topic-inner-description">
                                        Finally!<br>
                                        Are there any special recommendations for design or an updated guide that includes
                                        new preview sizes, including retina displays?
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tt-item-info info-bottom">
                            <a href="#" class="tt-icon-btn">
                                <i class="tt-icon"><svg>
                                        <use xlink:href="#icon-like"></use>
                                    </svg></i>
                                <span class="tt-text">671</span>
                            </a>
                            <a href="#" class="tt-icon-btn">
                                <i class="tt-icon"><svg>
                                        <use xlink:href="#icon-dislike"></use>
                                    </svg></i>
                                <span class="tt-text">39</span>
                            </a>
                            <a href="#" class="tt-icon-btn">
                                <i class="tt-icon"><svg>
                                        <use xlink:href="#icon-favorite"></use>
                                    </svg></i>
                                <span class="tt-text">12</span>
                            </a>
                            <div class="col-separator"></div>
                            <a href="#" class="tt-icon-btn tt-hover-02 tt-small-indent">
                                <i class="tt-icon"><svg>
                                        <use xlink:href="#icon-share"></use>
                                    </svg></i>
                            </a>
                            <a href="#" class="tt-icon-btn tt-hover-02 tt-small-indent">
                                <i class="tt-icon"><svg>
                                        <use xlink:href="#icon-flag"></use>
                                    </svg></i>
                            </a>
                            <a href="#" class="tt-icon-btn tt-hover-02 tt-small-indent">
                                <i class="tt-icon"><svg>
                                        <use xlink:href="#icon-reply"></use>
                                    </svg></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="tt-item tt-wrapper-success">
                    <div class="tt-single-topic">
                        <div class="tt-item-header pt-noborder">
                            <div class="tt-item-info info-top">
                                <div class="tt-avatar-icon">
                                    <i class="tt-icon"><svg>
                                            <use xlink:href="#icon-ava-t"></use>
                                        </svg></i>
                                </div>
                                <div class="tt-avatar-title">
                                    <a href="#">tesla02</a>
                                    <span class="tt-color13 tt-badge">best answer</span>
                                </div>
                                <a href="#" class="tt-info-time">
                                    <i class="tt-icon"><svg>
                                            <use xlink:href="#icon-time"></use>
                                        </svg></i>6 Jan,2019
                                </a>
                            </div>
                        </div>
                        <div class="tt-item-description">
                            Finally!<br>
                            Are there any special recommendations for design or an updated guide that includes new preview
                            sizes, including retina displays?
                        </div>
                        <div class="tt-item-info info-bottom">
                            <a href="#" class="tt-icon-btn">
                                <i class="tt-icon"><svg>
                                        <use xlink:href="#icon-like"></use>
                                    </svg></i>
                                <span class="tt-text">671</span>
                            </a>
                            <a href="#" class="tt-icon-btn">
                                <i class="tt-icon"><svg>
                                        <use xlink:href="#icon-dislike"></use>
                                    </svg></i>
                                <span class="tt-text">39</span>
                            </a>
                            <a href="#" class="tt-icon-btn">
                                <i class="tt-icon"><svg>
                                        <use xlink:href="#icon-favorite"></use>
                                    </svg></i>
                                <span class="tt-text">12</span>
                            </a>
                            <div class="col-separator"></div>
                            <a href="#" class="tt-icon-btn tt-hover-02 tt-small-indent">
                                <i class="tt-icon"><svg>
                                        <use xlink:href="#icon-share"></use>
                                    </svg></i>
                            </a>
                            <a href="#" class="tt-icon-btn tt-hover-02 tt-small-indent">
                                <i class="tt-icon"><svg>
                                        <use xlink:href="#icon-flag"></use>
                                    </svg></i>
                            </a>
                            <a href="#" class="tt-icon-btn tt-hover-02 tt-small-indent">
                                <i class="tt-icon"><svg>
                                        <use xlink:href="#icon-reply"></use>
                                    </svg></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="tt-item tt-wrapper-danger">
                    <div class="tt-single-topic">
                        <div class="tt-item-header pt-noborder">
                            <div class="tt-item-info info-top">
                                <div class="tt-avatar-icon">
                                    <i class="tt-icon"><svg>
                                            <use xlink:href="#icon-ava-u"></use>
                                        </svg></i>
                                </div>
                                <div class="tt-avatar-title">
                                    <a href="#">usain31</a>
                                </div>
                                <a href="#" class="tt-info-time">
                                    <i class="tt-icon"><svg>
                                            <use xlink:href="#icon-time"></use>
                                        </svg></i>6 Jan,2019
                                </a>
                            </div>
                        </div>
                        <div class="tt-item-description">
                            This post has been flagged by a moderator, received too many downvotes.
                        </div>
                        <div class="row">
                            <div class="col-auto">
                                <div class="tt-item-info info-bottom">
                                    <a href="#" class="tt-icon-btn">
                                        <i class="tt-icon"><svg>
                                                <use xlink:href="#icon-dislike"></use>
                                            </svg></i>
                                        <span class="tt-text">39</span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-auto ml-auto">
                                <a href="#" class="btn btn-primary tt-offset-27">Show Reply</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tt-item">
                    <div class="tt-single-topic">
                        <div class="tt-item-header pt-noborder">
                            <div class="tt-item-info info-top">
                                <div class="tt-avatar-icon">
                                    <i class="tt-icon"><svg>
                                            <use xlink:href="#icon-ava-f"></use>
                                        </svg></i>
                                </div>
                                <div class="tt-avatar-title">
                                    <a href="#">kolis27</a>
                                </div>
                                <a href="#" class="tt-info-time">
                                    <i class="tt-icon"><svg>
                                            <use xlink:href="#icon-time"></use>
                                        </svg></i>6 Jan,2019
                                </a>
                            </div>
                        </div>
                        <div class="tt-item-description">
                            <p>
                                It’s too big preview image, it should be smaller even five in row. On one page there are 30
                                items to 60 pages it is 1800 items in categories eg in Add-Ons category have 22749 items,
                                why not see all of them but only those 1800 items? This is a bad thing.
                            </p>
                            <div class="row tt-offset-37">
                                <div class="col-lg-10">
                                    <div class="tt-gallery-layout">
                                        <div class="tt-item">
                                            <a href="images/single-topic-img03.jpg" class="tt-gallery-obj"><img
                                                    src="images/single-topic-img03.jpg" alt=""></a>
                                        </div>
                                        <div class="tt-item">
                                            <a href="images/single-topic-img04.jpg" class="tt-gallery-obj"><img
                                                    src="images/single-topic-img04.jpg" alt=""></a>
                                        </div>
                                        <div class="tt-item">
                                            <a href="images/single-topic-img05.jpg" class="tt-gallery-obj"><img
                                                    src="images/single-topic-img05.jpg" alt=""></a>
                                        </div>
                                        <div class="tt-item">
                                            <a href="images/single-topic-img06.jpg" class="tt-gallery-obj"><img
                                                    src="images/single-topic-img06.jpg" alt=""></a>
                                        </div>
                                        <div class="tt-item">
                                            <a href="images/single-topic-img07.jpg" class="tt-gallery-obj"><img
                                                    src="images/single-topic-img07.jpg" alt=""></a>
                                        </div>
                                        <div class="tt-item">
                                            <a href="images/single-topic-img08.jpg" class="tt-gallery-obj"><img
                                                    src="images/single-topic-img08.jpg" alt=""></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tt-item-info info-bottom">
                            <a href="#" class="tt-icon-btn">
                                <i class="tt-icon"><svg>
                                        <use xlink:href="#icon-like"></use>
                                    </svg></i>
                                <span class="tt-text">671</span>
                            </a>
                            <a href="#" class="tt-icon-btn">
                                <i class="tt-icon"><svg>
                                        <use xlink:href="#icon-dislike"></use>
                                    </svg></i>
                                <span class="tt-text">39</span>
                            </a>
                            <a href="#" class="tt-icon-btn">
                                <i class="tt-icon"><svg>
                                        <use xlink:href="#icon-favorite"></use>
                                    </svg></i>
                                <span class="tt-text">12</span>
                            </a>
                            <div class="col-separator"></div>
                            <a href="#" class="tt-icon-btn tt-hover-02 tt-small-indent">
                                <i class="tt-icon"><svg>
                                        <use xlink:href="#icon-share"></use>
                                    </svg></i>
                            </a>
                            <a href="#" class="tt-icon-btn tt-hover-02 tt-small-indent">
                                <i class="tt-icon"><svg>
                                        <use xlink:href="#icon-flag"></use>
                                    </svg></i>
                            </a>
                            <a href="#" class="tt-icon-btn tt-hover-02 tt-small-indent">
                                <i class="tt-icon"><svg>
                                        <use xlink:href="#icon-reply"></use>
                                    </svg></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
            {{-- <div class="tt-wrapper-inner">
                <h4 class="tt-title-separator"><span>You’ve reached the end of replies</span></h4>
            </div>
            <div class="tt-topic-list">
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
            </div> --}}
            <div class="tt-wrapper-inner">
                <div class="pt-editor form-default">
                    <h6 class="pt-title">Post Your Reply</h6>
                    {{-- <div class="pt-row">
                        <div class="col-left">
                            <ul class="pt-edit-btn">
                                <li><button type="button" class="btn-icon">
                                        <svg class="tt-icon">
                                            <use xlink:href="#icon-quote"></use>
                                        </svg>
                                    </button></li>
                                <li><button type="button" class="btn-icon">
                                        <svg class="tt-icon">
                                            <use xlink:href="#icon-bold"></use>
                                        </svg>
                                    </button></li>
                                <li><button type="button" class="btn-icon">
                                        <svg class="tt-icon">
                                            <use xlink:href="#icon-italic"></use>
                                        </svg>
                                    </button></li>
                                <li><button type="button" class="btn-icon">
                                        <svg class="tt-icon">
                                            <use xlink:href="#icon-share_topic"></use>
                                        </svg>
                                    </button></li>
                                <li><button type="button" class="btn-icon">
                                        <svg class="tt-icon">
                                            <use xlink:href="#icon-blockquote"></use>
                                        </svg>
                                    </button></li>
                                <li><button type="button" class="btn-icon">
                                        <svg class="tt-icon">
                                            <use xlink:href="#icon-performatted"></use>
                                        </svg>
                                    </button></li>
                                <li class="hr"></li>
                                <li><button type="button" class="btn-icon">
                                        <svg class="tt-icon">
                                            <use xlink:href="#icon-upload_files"></use>
                                        </svg>
                                    </button></li>
                                <li><button type="button" class="btn-icon">
                                        <svg class="tt-icon">
                                            <use xlink:href="#icon-bullet_list"></use>
                                        </svg>
                                    </button></li>
                                <li><button type="button" class="btn-icon">
                                        <svg class="tt-icon">
                                            <use xlink:href="#icon-heading"></use>
                                        </svg>
                                    </button></li>
                                <li><button type="button" class="btn-icon">
                                        <svg class="tt-icon">
                                            <use xlink:href="#icon-horizontal_line"></use>
                                        </svg>
                                    </button></li>
                                <li><button type="button" class="btn-icon">
                                        <svg class="tt-icon">
                                            <use xlink:href="#icon-emoticon"></use>
                                        </svg>
                                    </button></li>
                                <li><button type="button" class="btn-icon">
                                        <svg class="tt-icon">
                                            <use xlink:href="#icon-settings"></use>
                                        </svg>
                                    </button></li>
                                <li><button type="button" class="btn-icon">
                                        <svg class="tt-icon">
                                            <use xlink:href="#icon-color_picker"></use>
                                        </svg>
                                    </button></li>
                            </ul>
                        </div>
                        <div class="col-right tt-hidden-mobile">
                            <a href="#" class="btn btn-primary">Preview</a>
                        </div>
                    </div> --}}
                    <div class="form-group">
                        <textarea name="message" class="form-control" rows="5" placeholder="Lets get started"></textarea>
                    </div>
                    <div class="pt-row">
                        <div class="col-auto">
                            <div class="checkbox-group">
                                <input type="checkbox" id="checkBox21" name="checkbox" checked="">
                                <label for="checkBox21">
                                    <span class="check"></span>
                                    <span class="box"></span>
                                    <span class="tt-text">Subscribe to this topic.</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-auto">
                            <a href="#" class="btn btn-secondary btn-width-lg">Reply</a>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="tt-topic-list tt-ofset-30">
                <div class="tt-list-search">
                    <div class="tt-title">Suggested Topics</div>
                    <!-- tt-search -->
                    <div class="tt-search">
                        <form class="search-wrapper">
                            <div class="search-form">
                                <input type="text" class="tt-search__input" placeholder="Search for topics">
                                <button class="tt-search__btn" type="submit">
                                    <svg class="tt-icon">
                                        <use xlink:href="#icon-search"></use>
                                    </svg>
                                </button>
                                <button class="tt-search__close">
                                    <svg class="tt-icon">
                                        <use xlink:href="#icon-cancel"></use>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- /tt-search -->
                </div>
                <div class="tt-list-header tt-border-bottom">
                    <div class="tt-col-topic">Topic</div>
                    <div class="tt-col-category">Category</div>
                    <div class="tt-col-value hide-mobile">Likes</div>
                    <div class="tt-col-value hide-mobile">Replies</div>
                    <div class="tt-col-value hide-mobile">Views</div>
                    <div class="tt-col-value">Activity</div>
                </div>
                <div class="tt-item">
                    <div class="tt-col-avatar">
                        <svg class="tt-icon">
                            <use xlink:href="#icon-ava-n"></use>
                        </svg>
                    </div>
                    <div class="tt-col-description">
                        <h6 class="tt-title"><a href="#">
                                Does Envato act against the authors of Envato markets?
                            </a></h6>
                        <div class="row align-items-center no-gutters hide-desktope">
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
                        <h6 class="tt-title"><a href="#">
                                <svg class="tt-icon">
                                    <use xlink:href="#icon-locked"></use>
                                </svg>
                                We Want to Hear From You! What Would You Like?
                            </a></h6>
                        <div class="row align-items-center no-gutters hide-desktope">
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
                    <div class="tt-col-value hide-mobile">674</div>
                    <div class="tt-col-value tt-color-select  hide-mobile">29</div>
                    <div class="tt-col-value hide-mobile">1.3k</div>
                    <div class="tt-col-value hide-mobile">2d</div>
                </div>
                <div class="tt-item">
                    <div class="tt-col-avatar">
                        <svg class="tt-icon">
                            <use xlink:href="#icon-ava-j"></use>
                        </svg>
                    </div>
                    <div class="tt-col-description">
                        <h6 class="tt-title"><a href="#">
                                Seeking partner backend developer
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
                                <div class="tt-value">2d</div>
                            </div>
                        </div>
                    </div>
                    <div class="tt-col-category"><span class="tt-color13 tt-badge">movies</span></div>
                    <div class="tt-col-value hide-mobile">278</div>
                    <div class="tt-col-value tt-color-select  hide-mobile">27</div>
                    <div class="tt-col-value hide-mobile">1.4k</div>
                    <div class="tt-col-value hide-mobile">2d</div>
                </div>
                <div class="tt-item">
                    <div class="tt-col-avatar">
                        <svg class="tt-icon">
                            <use xlink:href="#icon-ava-t"></use>
                        </svg>
                    </div>
                    <div class="tt-col-description">
                        <h6 class="tt-title"><a href="#">
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
                        <h6 class="tt-title"><a href="#">
                                <svg class="tt-icon">
                                    <use xlink:href="#icon-verified"></use>
                                </svg>
                                Microsoft Word and Power Point
                            </a></h6>
                        <div class="row align-items-center no-gutters hide-desktope">
                            <div class="col-11">
                                <ul class="tt-list-badge">
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
                <div class="tt-row-btn">
                    <button type="button" class="btn-icon js-topiclist-showmore">
                        <svg class="tt-icon">
                            <use xlink:href="#icon-load_lore_icon"></use>
                        </svg>
                    </button>
                </div>
            </div> --}}
        </div>
    </main>

@endsection

<script>
    function vote(element){
       
        const postId = element.dataset.postId;
     
        const voteType = element.dataset.voteType;
        const url = `/posts/${postId}/vote`;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(
                        { 
                            vote_type: voteType ,
                            post_id:postId
                        })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        if (data.redirect) {
                            window.location.href = data.redirect; 
                        } 
                    }else{
                        const upvoteElement = document.getElementById('up_vote_span');
                        if (upvoteElement && data.upvotes !== undefined) {
                            upvoteElement.textContent = data.upvotes;
                        }

                        const downvoteElement = document.getElementById('down_vote_span');
                        if (downvoteElement && data.downvotes !== undefined) {
                            downvoteElement.textContent = data.downvotes;
                        }
                    }
                    
                })
                .catch(error => {
                    console.error('Error voting:', error);
                });
    }
</script>