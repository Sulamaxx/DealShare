<?php

use App\Http\Controllers\admin\AuthenticationController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\DealsController;
use App\Http\Controllers\admin\SettingsController;
use App\Http\Controllers\admin\UsersController;
use App\Http\Controllers\admin\BadgeController;
use App\Http\Controllers\AiapplicationController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\ComponentspageController;
use App\Http\Controllers\CryptocurrencyController;
use App\Http\Controllers\DealHelpfulController;
use App\Http\Controllers\FormsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\UserFrontController;
use App\Http\Controllers\UserPostController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/run-migrations', function () {
    // Run the migrations
    Artisan::call('migrate');
    return 'Migrations have been successfully run!';
});

Route::get('/run-setting-seeder', function () {
    Artisan::call('db:seed', [
        '--class' => 'SettingSeeder',
        '--force' => true // Required for production environment
    ]);

    return response()->json(['message' => 'SettingSeeder executed successfully']);
});

Route::get('/run-seeders', function () {
    // Run the seeders
    Artisan::call('db:seed');
    return 'Seeders have been successfully run!';
});

Route::get('/run-storage-link', function () {
    $target = storage_path('app/public');
    $link = public_path('storage');

    if (!file_exists($link)) {
        symlink($target, $link);
        return 'Storage link created!';
    }

    return 'Storage link already exists!';
});

Route::get('/', [UserFrontController::class, 'index'])->name('home');
Route::get('/new-deals', [UserPostController::class, 'newDeals'])->name('new-deals');
Route::get('/popular-deals', [UserPostController::class, 'popularDeals'])->name('popular-deals');
Route::get('/highly-voted-deals', [UserPostController::class, 'highlyVotedDeals'])->name('highly-voted-deals');
Route::get('/deals', [UserPostController::class, 'search'])->name('deals.index');
Route::get('/pages', [UserFrontController::class, 'pages'])->name('pages');

Route::get('/users/{id}/profile-data', [UserController::class, 'getProfileData']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/my-deals', [UserPostController::class, 'myDeals'])->name('my-deals');
    Route::get('/my-activities', [UserPostController::class, 'myActivities'])->name('my-activities');
    Route::get('/create-deals', [UserPostController::class, 'index'])->name('create-deals');
    // Route::post('/posts', [UserPostController::class, 'store'])->name('posts.store');
    Route::resource('posts', UserPostController::class);
    Route::post('/posts/update', [UserPostController::class, 'update'])->name('posts.update');
    Route::get('/edit-deals/{id}', [UserPostController::class, 'edit'])->name('edit-deals');
    Route::post('/posts/{post}/report', [UserPostController::class, 'report']);

    Route::post('/deals/{deal}/mark-helpful', [DealHelpfulController::class, 'mark'])->name('deals.markHelpful');
    Route::delete('/deals/{deal}/unmark-helpful', [DealHelpfulController::class, 'unmark'])->name('deals.unmarkHelpful');
    Route::post('/start-thread', [ThreadController::class, 'store']);
    Route::get('/comments/{post}', [ThreadController::class, 'index']);
    Route::post('/comments', [ThreadController::class, 'storeChild']);
    Route::post('/comments/{comment}/report', [ThreadController::class, 'report']);
    Route::post('/subscription', [SubscriptionController::class, 'index'])->name('subscription');
    Route::post('/subscription/destroy', [SubscriptionController::class, 'destroy'])->name('subscription.destroy');

    Route::put('/user/settings', [UserController::class, 'updateSettings'])->name('user.updateSettings');
});

Route::get('/view-deal/{id}/{title}', [UserPostController::class, 'view_deal'])->name('view-deal');
Route::get('/view-deal/{id}', [UserPostController::class, 'view_deal'])->name('view-deal');

Route::get('/approval-pending', function () {
    return view('auth.approval-pending');
});







// Admin Routes
// Authentication
Route::prefix('admin')->group(function () {
    Route::controller(AuthenticationController::class)->group(function () {
        // Route::get('/forgot-password', 'forgotPassword')->name('forgotPassword');
        Route::get('/login', 'signin')->name('admin.login');
        Route::post('/login', 'login')->name('admin.login');
        // Route::get('/sign-up', 'signup')->name('signup');
    });
});
Route::prefix('admin')->group(function () {
    Route::controller(AuthenticationController::class)
        ->middleware([AdminMiddleware::class])
        ->group(function () {
            // Route::get('/forgot-password', 'forgotPassword')->name('forgotPassword');
            Route::get('/logout', 'logout')->name('admin.logout');
        });
});
Route::controller(DealsController::class)
    ->middleware([AdminMiddleware::class])
    ->group(function () {
        Route::get('/admin', 'dealsList')->name('dealsList');
    });

// Users
Route::prefix('admin/users')
    ->middleware([AdminMiddleware::class])
    ->group(function () {
        Route::controller(UsersController::class)->group(function () {
            Route::get('/add-user', 'addUser')->name('addUser');
            Route::get('/users-grid', 'usersGrid')->name('usersGrid');
            Route::get('/users-list', 'usersList')->name('usersList');
            Route::get('/users-list-reported', 'reportedUsersList')->name('reportedUsersList');
            Route::get('/users-list-banned', 'bannedUsersList')->name('bannedUsersList');
            Route::get('/view-profile/{id}', 'viewProfile')->name('user.profile');
            Route::patch('/user/{id}/status', 'changeStatus')->name('user.status');
            Route::delete('/user/{id}', 'destroy')->name('user.delete');
            Route::post('/admin/users/store', 'store')->name('admin.users.store');
            Route::post('/users/{id}/warning-email', 'sendWarningEmail')->name('user.warning.email');
            Route::post('/users/{id}/ban-temp', 'temporaryBan')->name('user.ban.temp');
            Route::post('/users/{id}/ban', 'banUser')->name('user.ban');
            Route::patch('/user/{id}/activate',  'activate')->name('user.activate');
            Route::get('/update-profile/{id}', 'show')->name('updateProfile');
            Route::post('/update-profile/{id}', 'update')->name('admin.updateProfile');
        });
    });

// Deals
Route::prefix('admin/deals')
    ->middleware([AdminMiddleware::class])
    ->group(function () {
        Route::controller(DealsController::class)->group(function () {
            Route::get('/add-deals', 'addDeal')->name('addDeal');
            Route::get('/deals-list', 'dealsList')->name('dealsList');
            Route::get('/reports-list', 'reportsList')->name('reportsList');
            Route::get('/comment-reports-list', 'commentReportsList')->name('commentReportsList');
            Route::get('/view-deals', 'viewDeal')->name('viewDeal');
            // Route::get('/deals',  'index')->name('deals.index');
            Route::patch('/deals/status/{id}', 'updateStatus')->name('deal.status');
            Route::patch('/deals/reject/{id}', 'updateRejectStatus')->name('deal.reject');
            Route::get('/deals/{id}/view', 'show')->name('deal.profile');
            Route::get('/deals/{id}/edit', 'edit')->name('deal.edit');
            Route::delete('/deals/{id}', 'destroy')->name('deal.delete');
            Route::post('/update-deal/{id}', 'update')->name('admin.updateDeal');

            Route::put('/comments/{comment}', 'updateText')->name('comments.updateText');

            Route::patch('/reports/status/{id}', 'updateReportStatus')->name('report.status');
            Route::patch('/comment/reports/status/{id}', 'updateCommentReportStatus')->name('commentReport.status');
            Route::delete('/reports/{id}', 'destroyReport')->name('report.delete');
            Route::delete('/deactivate/{report}/{deal}', 'deactivateDeal')->name('report.deactivate');
            Route::delete('/deactivate-comment/{report}/{comment}', 'deactivateComment')->name('commentReport.deactivate');
        });
    });

// Settings

Route::prefix('admin/settings')
    ->middleware([AdminMiddleware::class])
    ->group(function () {
        Route::controller(SettingsController::class)->group(function () {
            Route::get('/popular-deals', 'popularDeals')->name('popularDeals');
            Route::post('/popular-deals/update', 'update')->name('admin.popularDeals.update');
            Route::get('/highly-voted-deals', 'highlyVotedDeals')->name('highlyVotedDeals');
            Route::post('/highly-voted-deals/update', 'highlyVotedUpdate')->name('admin.highlyVotedDeals.update');
            Route::get('/cms_setting', 'cmsSetting')->name('cmsSetting');
            Route::post('/cms_setting', 'saveCMSSetting')->name('settings.save');
            Route::get('/bottom-banner', 'bottomBanner')->name('bottomBanner');
            Route::post('/bottom-banner', 'updateBottomBanner')->name('admin.updateBottomBanner');
            Route::get('/top-banner', 'topBanner')->name('topBanner');
            Route::post('/top-banner', 'updateTopBanner')->name('admin.updateTopBanner');
        });
    });

Route::prefix('admin/badges')
    ->middleware([AdminMiddleware::class])
    ->group(function () {
        Route::controller(BadgeController::class)->group(function () {
            Route::get('/add-badge', 'addBadge')->name('addBadge');
            Route::post('/add-badge', 'store')->name('admin.addBadge');
            Route::get('/update-badge/{id}', 'show')->name('updateBadge');
            Route::post('/update-badge/{id}', 'update')->name('admin.updateBadge');
            Route::get('/badges', 'badgesList')->name('getBadges');
            Route::delete('/badges/{id}', 'destroy')->name('badge.delete');
        });
    });

Route::prefix('user/settings')
    ->middleware([AdminMiddleware::class])
    ->group(function () {
        Route::controller(UserController::class)->group(function () {
            //Route::put('/', 'updateSettings')->name('user.updateSettings');
        });
    });

Route::controller(HomeController::class)->group(function () {
    Route::get('calendar-Main', 'calendarMain')->name('calendarMain');
    Route::get('chatempty', 'chatempty')->name('chatempty');
    Route::get('chat-message', 'chatMessage')->name('chatMessage');
    Route::get('chat-profile', 'chatProfile')->name('chatProfile');
    Route::get('email', 'email')->name('email');
    Route::get('faq', 'faq')->name('faq');
    Route::get('gallery', 'gallery')->name('gallery');
    Route::get('image-upload', 'imageUpload')->name('imageUpload');
    Route::get('kanban', 'kanban')->name('kanban');
    Route::get('page-error', 'pageError')->name('pageError');
    Route::get('pricing', 'pricing')->name('pricing');
    Route::get('starred', 'starred')->name('starred');
    Route::get('terms-condition', 'termsCondition')->name('termsCondition');
    Route::get('veiw-details', 'veiwDetails')->name('veiwDetails');
    Route::get('widgets', 'widgets')->name('widgets');
});

// aiApplication
Route::prefix('aiapplication')->group(function () {
    Route::controller(AiapplicationController::class)->group(function () {
        Route::get('/code-generator', 'codeGenerator')->name('codeGenerator');
        Route::get('/code-generatornew', 'codeGeneratorNew')->name('codeGeneratorNew');
        Route::get('/image-generator', 'imageGenerator')->name('imageGenerator');
        Route::get('/text-generator', 'textGenerator')->name('textGenerator');
        Route::get('/text-generatornew', 'textGeneratorNew')->name('textGeneratorNew');
        Route::get('/video-generator', 'videoGenerator')->name('videoGenerator');
        Route::get('/voice-generator', 'voiceGenerator')->name('voiceGenerator');
    });
});


// chart
Route::prefix('chart')->group(function () {
    Route::controller(ChartController::class)->group(function () {
        Route::get('/column-chart', 'columnChart')->name('columnChart');
        Route::get('/line-chart', 'lineChart')->name('lineChart');
        Route::get('/pie-chart', 'pieChart')->name('pieChart');
    });
});

// Componentpage
Route::prefix('componentspage')->group(function () {
    Route::controller(ComponentspageController::class)->group(function () {
        Route::get('/alert', 'alert')->name('alert');
        Route::get('/avatar', 'avatar')->name('avatar');
        Route::get('/badges', 'badges')->name('badges');
        Route::get('/button', 'button')->name('button');
        Route::get('/calendar', 'calendar')->name('calendar');
        Route::get('/card', 'card')->name('card');
        Route::get('/carousel', 'carousel')->name('carousel');
        Route::get('/colors', 'colors')->name('colors');
        Route::get('/dropdown', 'dropdown')->name('dropdown');
        Route::get('/imageupload', 'imageUpload')->name('imageUpload');
        Route::get('/list', 'list')->name('list');
        Route::get('/pagination', 'pagination')->name('pagination');
        Route::get('/progress', 'progress')->name('progress');
        Route::get('/radio', 'radio')->name('radio');
        Route::get('/star-rating', 'starRating')->name('starRating');
        Route::get('/switch', 'switch')->name('switch');
        Route::get('/tabs', 'tabs')->name('tabs');
        Route::get('/tags', 'tags')->name('tags');
        Route::get('/tooltip', 'tooltip')->name('tooltip');
        Route::get('/typography', 'typography')->name('typography');
        Route::get('/videos', 'videos')->name('videos');
    });
});

// Dashboard
Route::prefix('cryptocurrency')->group(function () {
    Route::controller(CryptocurrencyController::class)->group(function () {
        Route::get('/wallet', 'wallet')->name('wallet');
    });
});

// Dashboard
Route::prefix('dashboard')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/index', 'index')->name('index');
        Route::get('/index-2', 'index2')->name('index2');
        Route::get('/index-3', 'index3')->name('index3');
        Route::get('/index-4', 'index4')->name('index4');
        Route::get('/index-5', 'index5')->name('index5');
        Route::get('/index-6', 'index6')->name('index6');
        Route::get('/index-7', 'index7')->name('index7');
        Route::get('/index-8', 'index8')->name('index8');
        Route::get('/index-9', 'index9')->name('index9');
    });
});

// Forms
Route::prefix('forms')->group(function () {
    Route::controller(FormsController::class)->group(function () {
        Route::get('/form', 'form')->name('form');
        Route::get('/form-layout', 'formLayout')->name('formLayout');
        Route::get('/form-validation', 'formValidation')->name('formValidation');
        Route::get('/wizard', 'wizard')->name('wizard');
    });
});

// invoice/invoiceList
Route::prefix('invoice')->group(function () {
    Route::controller(InvoiceController::class)->group(function () {
        Route::get('/invoice-add', 'invoiceAdd')->name('invoiceAdd');
        Route::get('/invoice-edit', 'invoiceEdit')->name('invoiceEdit');
        Route::get('/invoice-list', 'invoiceList')->name('invoiceList');
        Route::get('/invoice-preview', 'invoicePreview')->name('invoicePreview');
    });
});

// Settings
Route::prefix('settings')->group(function () {
    Route::controller(SettingsController::class)->group(function () {
        Route::get('/company', 'company')->name('company');
        Route::get('/currencies', 'currencies')->name('currencies');
        Route::get('/language', 'language')->name('language');
        Route::get('/notification', 'notification')->name('notification');
        Route::get('/notification-alert', 'notificationAlert')->name('notificationAlert');
        Route::get('/payment-gateway', 'paymentGateway')->name('paymentGateway');
        Route::get('/theme', 'theme')->name('theme');
    });
});

// Table
Route::prefix('table')->group(function () {
    Route::controller(TableController::class)->group(function () {
        Route::get('/table-basic', 'tableBasic')->name('tableBasic');
        Route::get('/table-data', 'tableData')->name('tableData');
    });
});

Route::post('/posts/{post}/vote', [UserPostController::class, 'vote'])->name('post.vote');
