<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');

    Route::resource('attachment', App\Http\Controllers\AttachmentController::class);
    Route::post('attachment/upload', [App\Http\Controllers\AttachmentController::class, 'upload'])->name('attachment.upload');
    Route::post('attachment/paginate', [App\Http\Controllers\AttachmentController::class, 'paginate'])->name('attachment.paginate');






    Route::resource('report', App\Http\Controllers\ReportController::class);
    Route::post('/report/paginate', [App\Http\Controllers\ReportController::class, 'paginate'])->name('report.paginate');
    Route::post('/report/repair', [App\Http\Controllers\ReportController::class, 'repair'])->name('report.repair');
    Route::post('/report/reject', [App\Http\Controllers\ReportController::class, 'reject'])->name('report.reject');
    Route::post('/report/done', [App\Http\Controllers\ReportController::class, 'done'])->name('report.done');
    // Route::patch('/report/{id}/balas', [App\Http\Controllers\ReportController::class, 'balasan'])->name('report.balasan');
    Route::get('/report/{report}/detail', [App\Http\Controllers\ReportController::class, 'detail'])->name('report.detail');
    Route::get('/report/{report}/balas', [App\Http\Controllers\ReportController::class, 'balas'])->name('report.balas');

    Route::post('/report/stored', [App\Http\Controllers\ReportController::class, 'stored'])->name('report.stored');
    Route::patch('/report/{report}/sync', [App\Http\Controllers\ReportController::class, 'sync'])->name('report.sync');
    Route::patch('/report/{report}/sync1', [App\Http\Controllers\ReportController::class, 'sync1'])->name('report.sync1');

    Route::resource('/terkirim', App\Http\Controllers\TerkirimController::class);
    Route::post('/terkirim/paginate', [App\Http\Controllers\TerkirimController::class, 'paginate'])->name('terkirim.paginate');

    Route::resource('/perbaikan', App\Http\Controllers\PerbaikanController::class);
    Route::post('/perbaikan/paginate', [App\Http\Controllers\PerbaikanController::class, 'paginate'])->name('perbaikan.paginate');
    Route::resource('/selesai', App\Http\Controllers\SelesaiController::class);
    Route::post('/selesai/paginate', [App\Http\Controllers\SelesaiController::class, 'paginate'])->name('selesai.paginate');
    Route::resource('/pending', App\Http\Controllers\PendingController::class);
    Route::post('/pending/paginate', [App\Http\Controllers\PendingController::class, 'paginate'])->name('pending.paginate');

    Route::resource('feedback', App\Http\Controllers\FeedbackController::class);

    Route::post('/feedback/paginate', [App\Http\Controllers\FeedbackController::class, 'paginate'])->name('feedback.paginate');
    Route::post('/feedback/repair', [App\Http\Controllers\FeedbackController::class, 'repair'])->name('feedback.repair');
    Route::post('/feedback/reject', [App\Http\Controllers\FeedbackController::class, 'reject'])->name('feedback.reject');
    Route::post('/feedback/done', [App\Http\Controllers\FeedbackController::class, 'done'])->name('feedback.done');
    Route::get('/feedback/create', [App\Http\Controllers\FeedbackController::class, 'create'])->name('feedback.create');
    Route::get('/feedback/{feedback}/detail', [App\Http\Controllers\FeedbackController::class, 'detail'])->name('feedback.detail');
    // Route::post('/feedback/store', [App\Http\Controllers\FeedbackController::class, 'store'])->name('feedback.store');

    Route::resource('system', App\Http\Controllers\SystemSectionController::class);
    Route::post('/system/paginate', [App\Http\Controllers\SystemSectionController::class, 'paginate'])->name('system.paginate');
    Route::post('/system/store', [App\Http\Controllers\SystemSectionController::class, 'store'])->name('system.store');






    Route::resource('job', App\Http\Controllers\FilterJobdeskController::class);
    Route::post('/job/paginate', [App\Http\Controllers\FilterJobdeskController::class, 'paginate'])->name('job.paginate');
    Route::post('/job/store', [App\Http\Controllers\FilterJobdeskController::class, 'store'])->name('job.store');





    Route::resource('division', App\Http\Controllers\DivisionController::class);
    Route::post('/division/paginate', [App\Http\Controllers\DivisionController::class, 'paginate'])->name('division.paginate');
    Route::post('/division/store', [App\Http\Controllers\DivisionController::class, 'store'])->name('division.store');


    Route::resource('position', App\Http\Controllers\PositionController::class);
    Route::post('/position/paginate', [App\Http\Controllers\PositionController::class, 'paginate'])->name('position.paginate');
    Route::post('/position/store', [App\Http\Controllers\PositionController::class, 'store'])->name('position.store');

    Route::resource('master-machines', App\Http\Controllers\MasterMachineController::class);
    Route::post('/master-machines/paginate', [App\Http\Controllers\MasterMachineController::class, 'paginate'])
        ->name('master-machines.paginate');

    Route::resource('master-regions', App\Http\Controllers\MasterRegionController::class);
    Route::post('/master-regions/paginate', [App\Http\Controllers\MasterRegionController::class, 'paginate'])
        ->name('master-regions.paginate');

    Route::resource('master-checksheet', App\Http\Controllers\CheckSheetMasterController::class);
    Route::post('/master-checksheet/paginate', [App\Http\Controllers\CheckSheetMasterController::class, 'paginate'])
        ->name('master-checksheet.paginate');

    Route::resource('master-checksheet-day', App\Http\Controllers\CheckSheetMasterDayController::class);
    Route::post('/master-checksheet-day/paginate', [App\Http\Controllers\CheckSheetMasterDayController::class, 'paginate'])
        ->name('master-checksheet-day.paginate');

    Route::post('/working-reports/{report}/fetch', [App\Http\Controllers\WorkingReportController::class, 'fetch'])->name('working-reports.fetch');
    Route::get('/working-reports/{report}/detail/', [App\Http\Controllers\WorkingReportController::class, 'detail'])->name('working-reports.detail');
    Route::resource('working-reports', App\Http\Controllers\WorkingReportController::class);
    Route::post('/working-reports/paginate', [App\Http\Controllers\WorkingReportController::class, 'paginate'])
        ->name('working-reports.paginate');

    Route::post('/checksheetday-results/autosave', [App\Http\Controllers\CheckSheetDayResultController::class, 'autosave'])->name('checksheetday-results.autosave');

    // Route::post('/checksheet-workresult/autosave', [App\Http\Controllers\ChecksheetWorkResultController::class, 'autosave'])->name('checksheet-workresult.autosave');
    Route::resource('checksheet-workresult', App\Http\Controllers\CheckSheetWorkResultController::class);
    Route::post('/checksheet-workresult/approve', [App\Http\Controllers\CheckSheetWorkResultController::class, 'approve'])->name('checksheet-workresult.approve');

    // Route::post('/checksheet-results/autosave', [App\Http\Controllers\CheckSheetResultController::class, 'autosave'])->name('checksheet-results.autosave');
    Route::resource('check-sheet', App\Http\Controllers\CheckSheetController::class);
    Route::post('/check-sheet/paginate', [App\Http\Controllers\CheckSheetController::class, 'paginate'])
        ->name('check-sheet.paginate');

    Route::resource('check-sheet-day', App\Http\Controllers\CheckSheetDayController::class);
    Route::post('/check-sheet-day/paginate', [App\Http\Controllers\CheckSheetDayController::class, 'paginate'])
        ->name('check-sheet-day.paginate');
        
    Route::post('/upload/autosave', [App\Http\Controllers\UploadController::class, 'autosave']) ->name('upload.autosave');

    Route::resource('warming-up', App\Http\Controllers\WarmingUpController::class);
    Route::post('/warming-up/paginate', [App\Http\Controllers\WarmingUpController::class, 'paginate'])
        ->name('warming-up.paginate');

    Route::post('/workresult/approve', [App\Http\Controllers\WorkResultController::class, 'approve'])->name('workresult.approve');
    Route::resource('work-results', App\Http\Controllers\WorkResultController::class);
    Route::post('/work-results/paginate', [App\Http\Controllers\WorkResultController::class, 'paginate'])
        ->name('work-results.paginate');

    Route::resource('verifikasi', App\Http\Controllers\VerifikasiController::class);
    Route::post('/verifikasi/paginate', [App\Http\Controllers\VerifikasiController::class, 'paginate'])->name('verifikasi.paginate');
    Route::get('/verifikasi/{verifikasi}/detail', [App\Http\Controllers\VerifikasiController::class, 'detail'])->name('verifikasi.detail');

    Route::resource('joblist', App\Http\Controllers\JobListController::class);
    Route::post('/joblist/paginate', [App\Http\Controllers\JobListController::class, 'paginate'])->name('joblist.paginate');
    Route::get('/joblist/{joblist}/detail', [App\Http\Controllers\JobListController::class, 'detail'])->name('joblist.detail');

    Route::get('/users', fn() => App\Models\User::get())->name('users');

    Route::resource('maintenance-orders', App\Http\Controllers\MaintenanceOrderController::class);
    Route::post('maintenance-orders/paginate', [App\Http\Controllers\MaintenanceOrderController::class, 'paginate'])
    ->name('maintenance-orders.paginate');

    Route::prefix('/superuser')->name('superuser.')->group(function () {
        Route::resource('permission', App\Http\Controllers\Superuser\PermissionController::class)->only([
            'index', 'store', 'update', 'destroy',
        ])->middleware(['permission:read permission']);

        Route::resource('role', App\Http\Controllers\Superuser\RoleController::class)->only([
            'index', 'store', 'update', 'destroy',
        ])->middleware(['permission:read role']);

        Route::patch('/role/{role}/detach/{permission}', [App\Http\Controllers\Superuser\RoleController::class, 'detach'])->name('role.detach')->middleware(['permission:update role']);

        Route::resource('user', App\Http\Controllers\Superuser\UserController::class)->only([
            'index', 'store', 'update', 'destroy',
        ])->middleware(['permission:read user']);

        Route::prefix('/user/{user}')->name('user.')->controller(App\Http\Controllers\Superuser\UserController::class)->middleware(['permission:update user'])->group(function () {
            Route::patch('/role/{role}/detach', 'detachRole')->name('role.detach');
            Route::patch('/permission/{permission}/detach', 'detachPermission')->name('permission.detach');
        });

        Route::patch('/menu/save', [App\Http\Controllers\Superuser\MenuController::class, 'save'])->name('menu.save')->middleware(['permission:update menu']);
        Route::resource('menu', App\Http\Controllers\Superuser\MenuController::class)->only([
            'index', 'store', 'update', 'destroy',
        ])->middleware(['permission:read menu']);
        Route::get('/menu/{menu}/counter', [App\Http\Controllers\Superuser\MenuController::class, 'counter'])->name('menu.counter');

        Route::prefix('/translation')->name('translation.')->controller(App\Http\Controllers\TranslationController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::patch('/', 'update')->name('update');
        });

        Route::get('/activity/login', [App\Http\Controllers\ActivityController::class, 'login'])->name('activity.login');

        Route::get('/user/{user}/menu', fn (App\Models\User $user) => $user->menus())->name('user.menu');
        Route::get('/permission/get', [App\Http\Controllers\Superuser\PermissionController::class, 'get'])->name('permission');
        Route::get('/role/get', [App\Http\Controllers\Superuser\RoleController::class, 'get'])->name('role');
        Route::post('/role/paginate', [App\Http\Controllers\Superuser\RoleController::class, 'paginate'])->name('role.paginate');
        Route::post('/user/paginate', [App\Http\Controllers\Superuser\UserController::class, 'paginate'])->name('user.paginate');
        Route::post('/activity/login', [App\Http\Controllers\ActivityController::class, 'logins'])->name('activity.login');
        Route::get('/menu/get', [App\Http\Controllers\Superuser\MenuController::class, 'get'])->name('menu');



    });
});
