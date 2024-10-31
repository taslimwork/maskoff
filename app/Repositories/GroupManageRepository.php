<?php
/**
 * ------------------------------------------------------------------------
 *  Namespace
 * ------------------------------------------------------------------------
 */
namespace App\Repositories;
/**
 * ------------------------------------------------------------------------
 *  Interface
 * ------------------------------------------------------------------------
 */
use App\Interfaces\GroupManageInterface;
use App\Models\Group;
use App\Models\GroupAdmin;
use App\Models\GroupMembers;

/**
 * ------------------------------------------------------------------------
 *  Models
 * ------------------------------------------------------------------------
 */
use App\Models\GroupType;
use App\Models\Invitation;
use App\Models\InvitationType;
use Illuminate\Support\Facades\Auth;

class GroupManageRepository implements GroupManageInterface{

    public function _getGroupType(){
        return GroupType::where('status', 1)->select('id', 'type', 'status')->get()->toArray();
    }

    public function _createGroup($group, $request){
        $group->name = $request->name;
        $group->type_id = $request->type;
        $group->created_by = Auth::id();
        $group->moto = $request->moto;
        $group->description = $request->description;
        $group->save();
        return $group;
    }

    public function _joinGroup($groupId, $userId){
        $member = new GroupMembers();
        $member->group_id = $groupId;
        $member->user_id = $userId;
        $member->is_admin = 1;
        $member->save();
        return $member;
    }

    public function _joinGroupAsMember($groupId, $userId){
        $member = new GroupMembers();
        $member->group_id = $groupId;
        $member->user_id = $userId;
        $member->is_admin = 2;
        $member->save();
        return $member;
    }

    public function _assignGroupAdmin($gruopId, $userId){
        $groupAdmin = new GroupAdmin();
        $groupAdmin->group_id = $gruopId;
        $groupAdmin->user_id = $userId;
        $groupAdmin->status = 1;
        $groupAdmin->save();
        return $groupAdmin;
    }

    public function _getAllGroupList(){
        return Group::where('status', 1)->select('name', 'moto', 'description', 'group_logo')->get();
    }

    public function _sendInvitation($inviteRequest){
        $group = Group::find($inviteRequest->group_id);
        $group->invitations()->create([
            'sender' => Auth::id(),
            'reciver' => $inviteRequest->invitee,
        ]);

        return $group;
    }
    public function _invitationActionAttempt($request){
        $invitation = Invitation::find($request->invitation_id);
        $invitation->action = ($request->action == 1) ? 1 : 2;
        $invitation->save();
        return $invitation;
    }

    public function _getGroup($id){
        return Group::find($id);
    }

    public function _getAllGroupJoinRequest($group, $search){
        $searchQry =  $group->invitations()->where('type', 2)
                ->when($search ?? null, function($query) use($search){
                    
                    $query->whereHas('sendPerson', function($qry)use($search){
                        $qry->where('username', "like", "%" . trim($search) . "%")
                            ->orWhereRaw("CONCAT(first_name, isNull(last_name)) like '%" . trim($search) . "%'");
                    });
                });
        return $searchQry->get();
    }

}
