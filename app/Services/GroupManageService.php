<?php
/**
 * ------------------------------------------------------------------------
 *  Namespace
 * ------------------------------------------------------------------------
 */
namespace App\Services;
/**
 * ------------------------------------------------------------------------
 *  Interfaces
 * ------------------------------------------------------------------------
 */
use App\Interfaces\GroupManageInterface;
use App\Models\Group;
use App\Models\GroupAdmin;
use App\Models\GroupFollower;
use App\Models\GroupMembers;
use App\Models\Invitation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupManageService{

    public $_groupManageInterface;

    public function __construct(GroupManageInterface $groupManageInterface){
        $this->_groupManageInterface = $groupManageInterface;
    }

    public function getGroupType(){
        return $this->_groupManageInterface->_getGroupType();
    }

    public function createGroup($request){
        $group = new Group();

        if ($request->group_logo) {
            $group->group_logo = request()->file('group_logo')->store('group_logo');
        }

        $createGroup = $this->_groupManageInterface->_createGroup($group, $request);
        $createGroup->group_logo_url = $createGroup->group_logo_url;
        
        $userId = Auth::id();

        $join = $this->_groupManageInterface->_joinGroup($createGroup->id, $userId);

        $admins = $this->_groupManageInterface->_assignGroupAdmin($createGroup->id, $userId);

        foreach ($request->hashtags as $tags) {
            $group->hashTags()->create([
                'tag_title' => $tags
            ]);
        }

        $createGroup->hash_tags = $createGroup->hashTags;

        $createGroup->admins = $admins;
        return $createGroup;
    }

    public function getAllGroupList() {
        $groups = $this->_groupManageInterface->_getAllGroupList();
        return $groups;
    }

    public function sendInvitation($request){
        $grpInvitations = $this->_groupManageInterface->_sendInvitation($request);
        $data = [];
        $allGrpInvitation = $grpInvitations->invitations->where('type', 1);
        foreach ($allGrpInvitation as $key => $invitation) {
            $data[] = $invitation->recivePerson->only('id', 'username', 'full_name', 'first_name', 'last_name', 'hashtag', 'profile_photo_url');
        }
        return $data;
    }

    public function invitationActionAttempt($request){
        $actionAttempt = $this->_groupManageInterface->_invitationActionAttempt($request);
        if ($actionAttempt->action == 1) {
            $groupId = $actionAttempt->invitation_id;
            $userId = Auth::id();
            $addMember = $this->_groupManageInterface->_joinGroupAsMember($groupId, $userId);
            $data = [
                'invitation' => [
                    'type' => "Group Invitation",
                    'groupDetails' => $actionAttempt->invitation->only('id', 'name', 'type_id', 'created_by', 'moto', 'description', 'status', 'group_logo_url'),
                    'action' => 'Accepted',
                    'action_id' => $actionAttempt->action,
                    'invited_by' => $actionAttempt->sendPerson->only('id', 'username', 'full_name', 'first_name', 'last_name', 'hashtag', 'profile_photo_url'),
                    'user' => $actionAttempt->recivePerson->only('id', 'username', 'full_name', 'first_name', 'last_name', 'hashtag', 'profile_photo_url'),
                ]
            ];
            return $data;
        }else {
            $data = [
                'invitation' => [
                    'type' => "Group Invitation",
                    'groupDetails' => $actionAttempt->invitation->only('id', 'name', 'type_id', 'created_by', 'moto', 'description', 'status', 'group_logo_url'),
                    'action' => 'Rejected',
                    'action_id' => $actionAttempt->action,
                    'invited_by' => $actionAttempt->sendPerson->only('id', 'username', 'full_name', 'first_name', 'last_name', 'hashtag', 'profile_photo_url'),
                    'user' => $actionAttempt->recivePerson->only('id', 'username', 'full_name', 'first_name', 'last_name', 'hashtag', 'profile_photo_url'),
                ]
            ];
            return $data;
        }
    }

    public function sendGroupJoinRequest($request){
        $group = $this->_groupManageInterface->_getGroup($request->group_id);
        return $group->invitations()->create([
            'type' => 2,
            'sender' => Auth::id()
        ]);
    }

    public function getAllGroupJoinRequest($id, $search){
        $users = [];
        $allReq = $this->_groupManageInterface->_getAllGroupJoinRequest($id, $search);
        foreach ($allReq as $key => $req) {
            $user = $req->sendPerson->only('full_name', 'username', 'id', 'profile_photo_url');
            if ($req->action == 1) {
                $status = 'Joined';
            }elseif($req->action == 2){
                $status = 'Rejected';
            }elseif ($req->action == 0) {
                $status = 'Pending';
            }
            $user['action_status'] = $status;
            $user['action'] = $req->action;
            $user['join_request_id'] = $req->id;
            $users[] = $user;
        }
        return $users;
    }

    public function groupJoinRequestActionAttempt($request){
        $invitation = Invitation::find($request->join_request_id);
        $invitation->reciver = Auth::id();
        $invitation->action_attempt_by = Auth::id();
        $invitation->action = $request->action;
        $invitation->save();

        $user = $invitation->sendPerson->only('full_name', 'username', 'id', 'profile_photo_url');
        if ($invitation->action == 1) {
            $status = 'Accepted';
        }elseif($invitation->action == 2){
            $status = 'Rejected';
        }
        $user['action_status'] = $status;
        $user['action'] = $invitation->action;
        return $user;
    }

    public function getGroupDetails($group, $perPage = null){
        $grpDetails = $group->only('name', 'description');
        $joinStatus = $group->invitations->where('model_type', 'App\Models\Group')
            ->where(function($query){
                $query->where('sender', Auth::id())
                ->orWhere('reciver', Auth::id());
            })
            ->pluck('action')->first();
        $grpDetails['members'] = GroupMembers::where('group_id', $group->id)->paginate($perPage)
        ->through(function($mamber){
            return [
                'user_id' => $mamber->user->id,
                'full_name' => $mamber->user->full_name,
                'username' => $mamber->user->username,
                'profile_photo_url' => $mamber->user->profile_photo_url,
            ];
        });
        if ($joinStatus) {
            if ($joinStatus == 0) {
                $grpDetails['reqStatus'] = "Requested";
                $grpDetails['posts'] = [];
            }elseif ($joinStatus == 1) {
                $grpDetails['reqStatus'] = "Joined";
    
            }elseif ($joinStatus == 2) {
                $grpDetails['reqStatus'] = "Declined";
                $grpDetails['posts'] = [];
            }
        }else{
            $grpDetails['reqStatus'] = null;
            $grpDetails['posts'] = [];
        }
        return $grpDetails;
    }

    public function followGroup($request) {
        $group = Group::find($request->group_id);
        $follower = GroupFollower::where('group_id', $request->group_id)->where('user_id', Auth::id())->first();
        if ($follower) {
            $follower->status = $follower->status == 1 ? 0 : 1;
            $follower->save();
            return [
                'user' => $follower->user->only('full_name', 'username', 'profile_photo_url'),
                'group' => $follower->group->only('name', 'moto', 'group_logo', 'description'),
                'status' => $follower->status,
                "follow_status" => $follower->status == 1 ? "Following" : "Follow",
            ];
        }else {
            $follower = GroupFollower::create([
                'group_id' => $request->group_id,
                'user_id' => Auth::id(),
            ]);
            return [
                'user' => $follower->user->only('full_name', 'username', 'profile_photo_url'),
                'group' => $follower->group->only('name', 'moto', 'group_logo', 'description'),
                'status' => $follower->status,
                "follow_status" => $follower->status == 1 ? "Following" : "Follow",
            ];
        }
    }

}