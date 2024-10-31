<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\PostStatusController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\StrategyManagementController;
use App\Http\Controllers\Admin\SupportController;

Route::redirect('/admin', 'admin/login');
Route::group(['prefix' => 'admin'], function () {
    Route::match(['get', 'post'], 'login', [HomeController::class, 'index'])->name('admin.login');

    Route::name('admin.')->middleware(['auth', 'isAdmin'])->group(function () {
        Route::get('dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
        Route::any('admin-profile', [HomeController::class, 'adminProfile'])->name('profile');
        Route::post('admin-change-password', [HomeController::class, 'adminChangePassword'])->name('changePassword');
        Route::post('logout', [HomeController::class, 'logout'])->name('logout');

        // user module
        Route::get('users', [UserController::class, 'userlist'])->name('users');
        Route::any('create-user', [UserController::class, 'createUser'])->name('createUser');
        Route::any('edit-user/{user}', [UserController::class, 'editUser'])->name('editUser');
        Route::delete('delete-user/{user}', [UserController::class, 'deleteUser'])->name('userDelete');
        Route::post('change-user-status/{user}', [UserController::class, 'changeUserStatus'])->name('changeUserStatus');
        Route::get('user-export/', [UserController::class, 'exportData'])->name('userExport');

        //Group Type
        Route::get('event-type', [EventController::class, "eventTypeList"])->name('eventTypes');
        Route::match(['get', 'post'], 'event-type/create', [EventController::class, 'eventTypeCreate'])->name('createEventType');
        Route::match(['get', 'post'], 'event-type/{eventType}/edit', [EventController::class, 'eventTypeEdit'])->name('editEventType');
        Route::post('event-type/change-status/{eventType}', [EventController::class, 'changeEventTypeStatus'])->name('changeEventTypeStatus');
        Route::delete('event-type/{eventType}', [EventController::class, 'deleteEventType'])->name('deleteEventType');

        /* Event Management */
        Route::get('events', [EventController::class, 'eventList'])->name('events');
        Route::any('create-event', [EventController::class, 'createEvent'])->name('createEvent');
        Route::any('edit-event/{event}', [EventController::class, 'editEvent'])->name('editEvent');
        Route::delete('delete-event/{event}', [EventController::class, 'deleteEvent'])->name('deleteEvent');
        Route::post('change-event-status/{event}', [EventController::class, 'changeEventStatus'])->name('changeEventStatus');
        // Route::post('event/update-status', [EventController::class, 'addAttendingStatus']);
        // Route::post('event/details/{id}', [EventController::class, 'eventDetails']);

        /* Strategy Management */
        Route::get('strategies', [StrategyManagementController::class, 'strategyList'])->name('strategies');
        Route::post('strategy/delete/{id}', [StrategyManagementController::class, 'strategyDestroy'])->name('strategy.destroy');
        Route::post('strategy-type/add', [StrategyManagementController::class, 'strategyTypeAdd'])->name('Strategy-type.add');
        Route::post('strategy-type/delete/{id}', [StrategyManagementController::class, 'strategyTypeDelete'])->name('strategy-type.destroy');

        Route::get('strategy-sub/{strategy_id}', [StrategyManagementController::class, 'strategySubList'])->name('strategy-sub');
        Route::post('strategy-sub/add', [StrategyManagementController::class, 'strategySubAdd'])->name('strategy-sub.add');
        Route::post('strategy-sub/update/{id}', [StrategyManagementController::class, 'strategySubUpdate'])->name('strategy-sub.update');
        Route::post('strategy-sub/delete/{id}', [StrategyManagementController::class, 'subStrategyDelete'])->name('strategy-sub.delete');


        // Master Data
        // Group Type
        Route::get('group-type', [GroupController::class, 'groupTypeList'])->name('groupTypes');
        Route::match(['get', 'post'], 'group-type/create', [GroupController::class, 'groupTypeCreate'])->name('createGroupType');
        Route::match(['get', 'post'], 'group-type/{groupType}/edit', [GroupController::class, 'groupTypeEdit'])->name('editGroupType');
        Route::post('group-type/change-status/{groupType}', [GroupController::class, 'changeGroupTypeStatus'])->name('changeGroupTypeStatus');
        Route::delete('group-type/{groupType}', [GroupController::class, 'deleteGroupType'])->name('deleteGroupType');

        //Group Management
        Route::get('groups', [GroupController::class, 'groupList'])->name('groups');
        Route::get('group/{group}/members', [GroupController::class, 'groupMembers'])->name('groupMembers');
        Route::post('group/members/change-status', [GroupController::class, 'groupMemberChangeStatus'])->name('groupMemberChangeStatus');
        Route::post('group/members/delete', [GroupController::class, 'groupMemberDelete'])->name('groupMemberDelete');
        Route::post('group/change-status/{group}', [GroupController::class, 'changeGroupStatus'])->name('changeGroupStatus');
        // faq module
        Route::resource('faq', FaqController::class);
        Route::post('faq/change-status/{faq}', [FaqController::class, 'changeFaqStatus'])->name('faq.changeFaqStatus');
        // Support Center
        Route::get('support-list', [SupportController::class, "contactUsList"])->name('supports');
        Route::post('support-list/change-status/{support}', [SupportController::class, "changeContactUsStatus"])->name('changeSupportStatus');
        //Report Type
        Route::get('report-type', [ReportController::class, 'reportTypeList'])->name('reportTypes');
        Route::post('report-type/create', [ReportController::class, "addReportType"])->name('createReportType');
        Route::post('report-type/edit/{reportType}', [ReportController::class, "editReportType"])->name('editReportType');
        Route::post('report-type/change-status/{reportType}', [ReportController::class, 'reportTypeChangeStatus'])->name('reportChangeStatus');
        Route::delete('report-type/{reportType}', [ReportController::class, 'reportTypeDelete'])->name('reportTypeDelete');
        //Reports
        Route::get('reports', [ReportController::class, 'reportList'])->name('reports');
        Route::post('reports/change-status/{report}', [ReportController::class, 'reportChangeStatus'])->name('reportChangeStatus');
        // Route::post('report-type/create', [ReportController::class, "addReportType"])->name('createReportType');
        // Route::post('report-type/edit/{reportType}', [ReportController::class, "editReportType"])->name('editReportType');
        // Route::post('report-type/change-status/{reportType}', [ReportController::class, 'reportTypeChangeStatus'])->name('reportChangeStatus');
        // Route::delete('report-type/{reportType}', [ReportController::class, 'reportTypeDelete'])->name('reportTypeDelete');
        // cms module
        Route::resource('cms', CmsController::class)->except(['update', 'show']);
        Route::post('cms/{slug}', [CmsController::class, 'update']);

        // setting module
        Route::any('setting', [SiteSettingController::class, 'setting'])->name('setting');

        /* Post Management */
        Route::get('post-statuses', [PostStatusController::class, 'postList'])->name('post-status');
        Route::post('post-status/update-status/{id}', [PostStatusController::class, 'changeBlockUnblockStatus'])->name('post-status.StatusChange');
        Route::delete('post-status/delete/{id}', [PostStatusController::class, 'postDestroy'])->name('post-status.destroy');
        Route::post('post-status/comment', [PostStatusController::class, 'postComment'])->name('post-status.comment');
        Route::delete('post-status/comment/delete/{id}', [PostStatusController::class, 'postCommentDelete'])->name('post-status.comment.delete');


    });
});
