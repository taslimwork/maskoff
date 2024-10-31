<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
/**
 * ------------------------------------------------------------------
 *  Interfaces
 * ------------------------------------------------------------------
 */
use App\Interfaces\UserInterface;
use App\Models\EventAttendingStatus;

/**
 * ------------------------------------------------------------------
 *  Models
 * ------------------------------------------------------------------
 */
use App\Models\HashTag;
use App\Models\Invitation;
use App\Models\PostStatus;
use App\Models\User;
/**
 * ------------------------------------------------------------------
 *  Services
 * ------------------------------------------------------------------
 */
use App\Services\GroupManageService;
/**
 * ------------------------------------------------------------------
 *  Others
 * ------------------------------------------------------------------
 */
use Illuminate\Http\Request;
/**
 * ------------------------------------------------------------------
 *  Facades
 * ------------------------------------------------------------------
 */
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * @tags Hashtags-Management
 */
class HashtagController extends BaseController
{

    public $_userInterface, $_groupManageService;

    public function __construct(UserInterface $userInterface, GroupManageService $groupManageService){
        $this->_userInterface = $userInterface;
        $this->_groupManageService = $groupManageService;
    }
    /**
     * @operationId Hashtag-Search
     * @authenticated
     * @param string $search_tag to filter the hashTtg.
     */
    public function hashTagSearch(Request $request){
        $validator = Validator::make($request->all(), [
            'search_tag' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return parent::sendError($validator->errors()->first(), $validator->errors(), 422);
        }

        try {
            $search = $request->search_tag;
            $user = HashTag::where('tag_title', "LIKE", "%" . trim($search) . "%")
                    ->paginate($this->per_page)
                    ->through(function($tag){
                        if ($tag->model_type === "App\Models\User") {
                            $model = 'App\Models\User';
                            $connection  = $this->checkConnection($tag, $model);
                            if ($connection) {
                                $connStatus = $connection->action == 1 ? 'Connected': ($connection->action == 2 ? 'Rejected' : 'Requested');
                            }else {
                                $connStatus = null;
                            }
                            
                            $data = [
                                'type' => 'Users',
                                'data' => $tag->model->only('full_name', 'username', 'profile_photo_url'),
                                'connection_status' => $connStatus,
                                'hash_tags' => $tag->model->hashTags,
                            ];
                        }
                        if ($tag->model_type === "App\Models\PostStatus") {
                            $friendsIds = $this->_userInterface->_getUserConnectedUserIds();
                            $data = [
                                'type' => 'Posts',
                                'connection_status' => null,
                                'hash_tags' => $tag->model->hashTags,
                                'data' => PostStatus::with(['postDetails','childComments','statusReaction'])
                                            ->where(function ($query) {
                                                $query->where('active', 1)
                                                    ->where('isSpam', 0)->where('parent_id', 0);
                                            })
                                            ->where(function ($query) use ($friendsIds) {
                                                $query->where('post_audience', 'friends')
                                                        ->whereIn('user_id', $friendsIds);
                                                $query->orWhere(function ($query1){
                                                    $query1->where('post_audience', 'public');
                                                });
                                            })
                                            ->where('id', $tag->model->id)
                                            ->get()
                            ];
                        }
                        if ($tag->model_type === "App\Models\Group") {
                            $model = 'App\Models\Group';
                            $connection  = $this->checkConnection($tag, $model);
                            if ($connection) {
                                $connStatus = $connection->action == 1 ? 'Connected': ($connection->action == 2 ? 'Rejected' : 'Requested');
                            }else {
                                $connStatus = null;
                            }
                            $data = [
                                'type' => 'Groups',
                                'data' => $this->_groupManageService->getGroupDetails($tag->model),
                                'hash_tags' => $tag->model->hashTags,
                                'connection_status' => $connStatus
                            ];
                        }
                        if ($tag->model_type === "App\Models\Event") {
                            $model = 'App\Models\Event';
                            $connection  = $this->checkConnection($tag, $model);
                            print_r($connection);exit;
                            if ($connection && $connection->active == 1) {
                                $connStatus = EventAttendingStatus::where('event_id', $tag->model_id)->where('user_id', auth()->id())->pluck('status');
                            }else {
                                $connStatus = null;
                            }
                            $data = [
                                'type' => 'Events',
                                'data' => $tag->model,
                                'hash_tags' => $tag->model->hashTags,
                                'connection_status' => $connStatus
                            ];
                        }
                        return $data;
                    });
            return parent::sendResponse($user, "Hashtag", 200);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }

    public function checkConnection($tag, $model){
        return Invitation::where('model_type', $model)
                    ->where('type', 1)->where('action', '1')
                    ->where(function($query)use($tag){
                        $query->where(function($qry)use($tag){
                            $qry->where('sender', $tag->sender)
                            ->where('reciver', Auth::id());
                        })
                        ->orWhere(function($qry)use($tag){
                            $qry->where('sender', Auth::id())
                            ->orWhere('reciver', $tag->reciver);
                        });
                    })->first();
    }
}
