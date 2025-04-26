@extends('layouts.app')

@section('title', 'Forum')

@section('content')

    <main id="tt-pageContent" class="p-5">
        <div class="container">
            <div class="tt-wrapper-inner mb-5">
                <h1 class="tt-title-border">
                    Create New Deal
                </h1>
                <form class="form-default form-create-topic" action="{{ route('posts.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <!-- Deal Title -->
                    <div class="form-group">
                        <label for="inputTopicTitle">Deal Title</label>
                        <input type="text" name="title" class="form-control" id="inputTopicTitle"
                            placeholder="Subject of your topic" required>
                        <div class="tt-note">Describe your deal well, while keeping the subject short.</div>
                    </div>

                    <!-- Link -->
                    <div class="form-group">
                        <label for="inputDealLink">Deal Link</label>
                        <input type="text" name="link" class="form-control" id="inputDealLink"
                            placeholder="eg: https://example.com">
                    </div>

                    <!-- Discount Text -->
                    <div class="form-group">
                        <label for="inputDiscountText">Discount Text</label>
                        <input type="text" name="discount_text" class="form-control" id="inputDiscountText"
                            placeholder="eg: 20% OFF">
                    </div>

                    <!-- Price Saving -->
                    <div class="form-group">
                        <label for="inputPriceSaving">Price Saving</label>
                        <input type="text" name="price_saving" class="form-control" id="inputPriceSaving"
                            placeholder="eg: Save 800 LKR">
                    </div>

                    <!-- Deal Body -->
                    <div class="form-group">
                        <label for="inputDescription">Deal Description</label>
                        <textarea name="description" class="form-control" id="inputDescription" rows="5"
                            placeholder="Write details here..." required></textarea>
                    </div>

                    <!-- Category -->
                    <div class="form-group">
                        <label for="inputCategory">Category</label>
                        <select name="category" class="form-control" id="inputCategory" required>
                            <option value="">Select a Category</option>
                            <option value="Shopping Advice">Shopping Advice</option>
                            <option value="Product Reviews">Product Reviews</option>
                            <option value="Consumer Rights">Consumer Rights</option>
                            <!-- add more if you want -->
                        </select>
                    </div>

                    <!-- Image Upload -->
                    <div class="form-group">
                        <label for="inputImage">Upload Deal Image</label>
                        <input type="file" name="image" class="form-control-file" id="inputImage" accept="image/*">
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary btn-width-lg">Create Post</button>
                    </div>
                </form>

            </div>
            {{-- <div class="tt-topic-list tt-offset-top-30">
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
                        <div class="col-auto">
                            <ul class="tt-list-badge">
                                <li class="show-mobile"><a href="#"><span class="tt-color05 tt-badge">music</span></a></li>
                            </ul>
                        </div>
                        <div class="col-auto ml-auto show-mobile">
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
                        <div class="col-auto">
                            <ul class="tt-list-badge">
                                <li class="show-mobile"><a href="#"><span class="tt-color06 tt-badge">movies</span></a></li>
                            </ul>
                        </div>
                        <div class="col-auto ml-auto show-mobile">
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
                        <div class="col-auto">
                            <ul class="tt-list-badge">
                                <li class="show-mobile"><a href="#"><span class="tt-color03 tt-badge">exchange</span></a></li>
                                <li><a href="#"><span class="tt-badge">themeforest</span></a></li>
                                <li><a href="#"><span class="tt-badge">elements</span></a></li>
                            </ul>
                        </div>
                        <div class="col-auto ml-auto show-mobile">
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
                        <div class="col-auto">
                             <ul class="tt-list-badge">
                                <li class="show-mobile"><a href="#"><span class="tt-color07 tt-badge">video games</span></a></li>
                                <li><a href="#"><span class="tt-badge">videohive</span></a></li>
                                <li><a href="#"><span class="tt-badge">photodune</span></a></li>
                            </ul>
                        </div>
                        <div class="col-auto ml-auto show-mobile">
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
                        <div class="col-auto">
                            <ul class="tt-list-badge">
                                <li class="show-mobile"><a href="#"><span class="tt-color08 tt-badge">youtube</span></a></li>
                            </ul>
                        </div>
                        <div class="col-auto ml-auto show-mobile">
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

    {{-- <div id="js-popup-settings" class="tt-popup-settings">
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
		   <input type="text" name="name" class="form-control" id="settingsUserName" placeholder="azyrusmax">
		</div>
		<div class="form-group">
		    <label for="settingsUserEmail">Email</label>
		   <input type="text" name="name" class="form-control" id="settingsUserEmail" placeholder="Sample@sample.com">
		</div>
		<div class="form-group">
		    <label for="settingsUserPassword">Password</label>
		   <input type="password" name="name" class="form-control" id="settingsUserPassword" placeholder="************">
		</div>
		<div class="form-group">
		    <label for="settingsUserLocation">Location</label>
		   <input type="text" name="name" class="form-control" id="settingsUserLocation" placeholder="Slovakia">
		</div>
		<div class="form-group">
		    <label for="settingsUserWebsite">Website</label>
		   <input type="text" name="name" class="form-control" id="settingsUserWebsite" placeholder="Sample.com">
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
</div> --}}

@endsection
