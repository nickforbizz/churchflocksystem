<?php

use App\Http\Controllers\cms\AnnouncementController;
use App\Http\Controllers\cms\ChildController;
use App\Http\Controllers\cms\ChurchEventController;
use App\Http\Controllers\cms\DonationController;
use App\Http\Controllers\cms\EventAttendanceController;
use App\Http\Controllers\cms\GroupController;
use App\Http\Controllers\cms\HomecellController;
use App\Http\Controllers\cms\MemberController;
use App\Http\Controllers\cms\MinistryController;
use App\Http\Controllers\cms\NotificationController;
use App\Http\Controllers\cms\ParamController;
use App\Http\Controllers\cms\PermissionController;
use App\Http\Controllers\cms\UserController;
use App\Http\Controllers\cms\PostCategoryController;
use App\Http\Controllers\cms\PostController;
use App\Http\Controllers\cms\ProductCategoryController;
use App\Http\Controllers\cms\ReportController;
use App\Http\Controllers\cms\ReportCenterController;
use App\Http\Controllers\cms\RoleController;
use App\Http\Controllers\cms\SearchController;
use App\Http\Controllers\cms\ValuelistController;
use App\Http\Controllers\cms\WhatsAppController;
use App\Http\Controllers\frontend\ViewsController;
use App\Http\Controllers\HomeController;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/test', function () {
    // $admins = Role::whereIn('name', ['admin', 'superadmin'])
    // ->with('users')->get();
    $users = User::whereHas('roles', function ($query) {
        $query->whereIn('name', ['admin', 'superadmin']);
    })->get();
    return $users;
    // return what you want
});



Route::get('/optimize', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('optimize');
    Artisan::call('storage:link');
    Artisan::call('composer dump-autoload');
    return 'done';
});

Route::get('/flush-perms', function () {
    Artisan::call('permission:cache-reset');
    return 'Permissions cache flushed successfully!';
})->name('flush-perms');


// newsletter
Route::post('/newsletter/subscribe', [ViewsController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');


// Route::get('/', function () {
//     return view('welcome');
// });


// Frontend Views
Route::get('/', [ViewsController::class, 'index'])->name('wellcome');
Route::get('/about', [ViewsController::class, 'about'])->name('about');
Route::get('/blog/{id}', [ViewsController::class, 'getPost'])->name('blog');
Route::get('/blogs', [ViewsController::class, 'posts'])->name('blogs');
Route::prefix('web')->group(function () {

});

// Backend/CMS
Route::middleware('cms')->group(function () {

    Route::get('/home', [HomeController::class, 'cms'])->name('home');
    Route::get('/cms', [HomeController::class, 'cms'])->name('cms');
    Route::get('/dashboard/widget/{widget}', [HomeController::class, 'getWidgetData'])->name('dashboard.widget');
    Route::get('/search', [SearchController::class, 'search'])->name('search');
    Route::get('/send-whatsapp', [WhatsAppController::class, 'sendWhatsappMessage'])->name('send.whatsapp');


    // Downloadable Reports
    Route::get('reports/download/csv', [ReportController::class, 'downloadCsv'])->name('reports.download.csv');

    // Report Center - Unified Reporting Interface
    Route::prefix('report-center')->name('report-center.')->group(function () {
        Route::get('/', [ReportCenterController::class, 'index'])->name('index');
        Route::get('/export', [ReportCenterController::class, 'export'])->name('export');
        Route::get('/chart-data', [ReportCenterController::class, 'getChartData'])->name('chart-data');
        Route::get('/filter-options', [ReportCenterController::class, 'getFilterOptionsAjax'])->name('filter-options');
        Route::get('/dashboard-summary', [ReportCenterController::class, 'getDashboardSummary'])->name('dashboard-summary');
    });

    Route::get('/calendar', [ChurchEventController::class, 'showCalendar'])->name('calendar');
    Route::get('/calendar/events', [ChurchEventController::class, 'calendarEvents'])->name('calendar.events');
    Route::get('/members/search', [MemberController::class, 'search'])->name('members.search');
    Route::get('/members/export', [MemberController::class, 'export'])->name('members.export');
    Route::get('/groups/list', [GroupController::class, 'list'])->name('groups.list');
    Route::get('/groups/export', [GroupController::class, 'export'])->name('groups.export');
    
    // Valuelist API routes
    Route::get('/valuelists/types/search', [ValuelistController::class, 'searchTypes'])->name('valuelists.types.search');
    Route::get('/valuelists/types/next-index', [ValuelistController::class, 'getNextIndex'])->name('valuelists.types.nextIndex');


    // Resources Routes
    Route::resources([
        'users' => UserController::class,
        'members' => MemberController::class,
        'groups' => GroupController::class,
        'homecells' => HomecellController::class,
        'ministries' => MinistryController::class,
        'children' => ChildController::class,
        'events' => ChurchEventController::class,
        'eventAttendance' => EventAttendanceController::class,
        'donations' => DonationController::class,
        'announcements' => AnnouncementController::class,
        'params' => ParamController::class,
        'valuelists' => ValuelistController::class,

        'roles' => RoleController::class,
        'permissions' => PermissionController::class,
        'reports' => ReportController::class,
        'notifications' => NotificationController::class,

        'posts' => PostController::class,
        'postCategories' => PostCategoryController::class,
        'productCategories' => ProductCategoryController::class,
    ]);

    // downloadCalendarCsv
    Route::get('/events/download-attendance-csv', [ChurchEventController::class, 'downloadAttendanceCsv'])->name('events.downloadAttendanceCsv');
   
    Route::post('/notifications//mark-as-read', [NotificationController::class, 'markNotification'])->name('notifications.markNotification');
});

// Route for sending announcements to groups
Route::post('/announcements/{announcement}/send-to-groups', [AnnouncementController::class, 'sendToGroups'])->name('announcements.sendToGroups');
