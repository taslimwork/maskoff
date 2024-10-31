<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EventManagementController;
use App\Http\Controllers\API\GroupController;
use App\Http\Controllers\API\HashtagController;
use App\Http\Controllers\API\JournalController;
use App\Http\Controllers\API\PostStatusManagement;
use App\Http\Controllers\API\StrategyController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\UserNotificationsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('login', [AuthController::class,'login']);
Route::post('register', [AuthController::class,'register']);
Route::post('forgot-password', [AuthController::class,'forgotPassword']);
Route::post('otp-verify', [AuthController::class,'otpVerify']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);
Route::post('username-suggestions', [AuthController::class, 'getUsernameSuggestion']);

/* Event Management */
Route::get('event/all-list', [EventManagementController::class, 'allEventList']);
Route::get('event/type', [EventManagementController::class, 'getEventTypes']);


Route::middleware('auth:sanctum')->group(function(){
    Route::get('profile', [UserController::class,'profile']);
    Route::post('update-profile', [UserController::class,'updateProfile']);
    Route::post('delete-account', [UserController::class, 'deleteAccount']);
    Route::post('change-password', [AuthController::class, 'changePassword']);
    Route::post('contact', [UserController::class, 'contactUs']);
    // notification
    Route::get("notifications", [UserNotificationsController::class, "userNotificationList"]);
    Route::any("read-notification", [UserNotificationsController::class, "readNotification"]);
    Route::post("test-notification", [UserNotificationsController::class, "testNotification"]);

    /* Event Management */
    Route::post('event/create', [EventManagementController::class, 'createEvent']);
    Route::post('event/edit/{id}', [EventManagementController::class, 'editEvent']);
    Route::get('event/my-list', [EventManagementController::class, 'myEventList']);
    Route::post('event/update-status', [EventManagementController::class, 'addAttendingStatus']);
    Route::post('event/details/{id}', [EventManagementController::class, 'eventDetails']);
    Route::post('event/invite', [EventManagementController::class, 'sendEventInvitation']);
    Route::get('event/attending-status', [EventManagementController::class, 'eventAttendingStatus']);
    Route::post('event/attend', [EventManagementController::class, 'eventAttend']);


    /* Journal Management */
    Route::get('journal/list', [JournalController::class, 'journalList']);
    Route::post('journal/create', [JournalController::class, 'createJournal']);
    Route::post('journal/edit/{id}', [JournalController::class, 'editJournal']);

    /* Strategy Management */
    Route::get('strategy/list', [StrategyController::class, 'strategyList']);
    Route::get('strategy/sub-list/{strategy_id}/{strategy_type_id?}', [StrategyController::class, 'strategySubList']);

    /* Post Status Management */
    Route::get('post-status/list', [PostStatusManagement::class, 'postList']);
    Route::get('post-status/my-post', [PostStatusManagement::class, 'myPost']);
    Route::post('post-status/create', [PostStatusManagement::class, 'createPost']);
    Route::post('post-status/update', [PostStatusManagement::class, 'updatePost']);
    // Route::post('post-status/comment/{post_status_id}', [PostStatusManagement::class, 'createComment']);
    Route::post('post-status/reaction', [PostStatusManagement::class, 'addReaction']);
    Route::post('post-status/save-unsave', [PostStatusManagement::class, 'savePost']);

    //Interest Posts
    Route::get('interests-post', [PostStatusManagement::class, 'interestPosts']);

    /**
     * ----------------------------------------------------------------------------
     *      Users List
     * ----------------------------------------------------------------------------
     */
    Route::get('users', [UserController::class, 'getAllUsers']);
    Route::post('user/connect', [UserController::class, 'sendConnectRequest']);
    Route::get('user/connection-list', [UserController::class, 'getUserConnectionList']);
    Route::post('user/connection/action-attempt', [UserController::class, 'connectionActionAttempt']);
    Route::get('user/{id}/details', [UserController::class, 'getUserDetails']);
    /**
     * ----------------------------------------------------------------------------
     *      Group Management
     * ----------------------------------------------------------------------------
     */
    Route::get('group/types', [GroupController::class, 'getGroupType']);
    Route::post('group/create', [GroupController::class, 'createGroup']);
    Route::get('groups', [GroupController::class, 'groupList']);
    Route::get('group/{id}/details', [GroupController::class, 'getGroupDetails']);

    Route::post('group/invite', [GroupController::class, 'sendGroupInvitation']);
    Route::post('group/invitation/action-attempt', [GroupController::class, 'invitationActionAttempt']);
    Route::post('group/join-request', [GroupController::class, 'sendGroupJoinRequest']);
    Route::get('group/{id}/join-request-list', [GroupController::class, 'getAllGroupJoinRequest']);
    Route::post('group/join-request/action-attempt', [GroupController::class, 'groupJoinRequestActionAttempt']);
    Route::post('group/follow', [GroupController::class, 'followGroup']);
    /**
     * ------------------------------------------------------------------------------
     *      HashTags Management
     * ------------------------------------------------------------------------------
     */
    Route::get('hashtags/search', [HashtagController::class, 'hashTagSearch']);

    Route::post('logout', [UserController::class, 'logout']);
});


