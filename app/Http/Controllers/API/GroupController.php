<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Invitation;
use App\Services\GroupManageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
/**
 * @tags Group Management
 */
class GroupController extends BaseController
{
    protected $_groupManageService;

    public function __construct(GroupManageService $groupManageService){
        $this->_groupManageService = $groupManageService;
    }

    /**
     * @operationId getGroupType
     * @authenticated
     */
    public function getGroupType(){
        try {
            $data = $this->_groupManageService->getGroupType();
            return parent::sendResponse($data, 'Group type retrive successfully', 200);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }
    /**
     * @operationId createGroup
     * @authenticated
     */
    public function createGroup(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:100',
            'type' => 'required',
            'group_logo' => 'nullable|image|mimes:png,jpg,jpeg,gif',
            'moto' => 'required|string|min:3',
            'description' => 'required|string|min:10',
            'hashtags' => 'required|array'
        ]);

        if ($validator->fails()) {
            return parent::sendError($validator->errors()->first(), $validator->errors(), 422);
        }

        try {
            $data = $this->_groupManageService->createGroup($request);
            return parent::sendResponse($data, 'Group created successfully', 200);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }

    /**
     * @operationId groupList
     * @authenticated
     */
    public function groupList() {
        try {
            $data = $this->_groupManageService->getAllGroupList();
            return parent::sendResponse($data, 'Groups retrived successfully', 200);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }

    /**
     * @operationId sendGroupInvitation
     * @authenticated
     */
    public function sendGroupInvitation(Request $request) {
        $validator = Validator::make($request->all(),[
            'invitee' => 'required',
            'group_id' => 'required',
        ]);

        if ($validator->fails()) {
            return parent::sendError($validator->errors()->first(), $validator->errors(), 422);
        }

        $group = Group::find($request->group_id);
        $isInvited = $group->invitations()->where('sender', Auth::id())->where('reciver', $request->invitee)->where('action', '0')->exists();
        
        if ($isInvited) {
            return parent::sendError("Invitation already send.", [], 422);
        }
        $isMember = $group->invitations()->where('sender', Auth::id())->where('reciver', $request->invitee)->where('action', '1')->exists();
        
        if ($isMember) {
            return parent::sendError("Already in group", [], 422);
        }
        try {
            $invitation = $this->_groupManageService->sendInvitation($request);
            return parent::sendResponse($invitation, "Invitation sent successfully", 200);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }
    /**
     * @operationId invitationActionAttempt
     * @authenticated
     */
    public function invitationActionAttempt(Request $request) {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:1,2',
            'invitation_id' => 'required'
        ]);

        if ($validator->fails()) {
            return parent::sendError($validator->errors()->first(), $validator->errors(), 422);
        }

        try {
            $actionAttempt = $this->_groupManageService->invitationActionAttempt($request);
            $msg = ($request->action == 1) ? "Invitation accepted" : "Invitation rejected";
            return parent::sendResponse($actionAttempt, $msg, 200);

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }

    /**
     * @operationId sendGroupJoinRequest
     * @authenticated
     */
    public function sendGroupJoinRequest(Request $request){
        $validator = Validator::make($request->all(), [
            'group_id' => 'required',
        ]);

        if ($validator->fails()) {
            return parent::sendError($validator->errors()->first(), $validator->errors(), 422);
        }

        $group = Group::find($request->group_id);
        
        if (Auth::id() == $group->created_by) {
            return parent::sendError('You have already joined', [], 422);
        }

        try {
            $isExistsReq = Invitation::where('type', 2)->where('sender', Auth::id())->exists();

            if ($isExistsReq) {
                $req = Invitation::where('type', 2)->where('sender', Auth::id())->first();
                if ($req->action == 1) {
                    return parent::sendError('You have already joined', [], 422);
                }
                return parent::sendError('Join request already send', [], 422);
            }

            $sendReq = $this->_groupManageService->sendGroupJoinRequest($request);
            return parent::sendResponse($sendReq, 'Join request sent', 200);

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }

    /**
     * @operationId getAllGroupJoinRequest
     * @authenticated
     */
    public function getAllGroupJoinRequest(Group $id, Request $request){
        try {
            $allReq = $this->_groupManageService->getAllGroupJoinRequest($id, $request->search);
            return parent::sendResponse($allReq, 'Join request sent', 200);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }
    /**
     * @operationId groupJoinRequestActionAttempt
     * @authenticated
     */
    public function groupJoinRequestActionAttempt(Request $request){
        $validator = Validator::make($request->all(), [
            'join_request_id' => 'required',
            'action' => 'required|in:1,2',
        ]);

        if ($validator->fails()) {
            return parent::sendError($validator->errors()->first(), $validator->errors(), 422);
        }

        $req = Invitation::find($request->join_request_id);

        if ($req->sender == Auth::id()) {
            return parent::sendError("You does't have right permission", [], 422);
        }

        try {
            $response = $this->_groupManageService->groupJoinRequestActionAttempt($request);
            if ($request->action == 1) {
                $msg = "Request accepted";
            }elseif ($request->action == 2) {
                $msg = "Request rejected";
            }
            return parent::sendResponse($response, $msg, 200);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }
    /**
     * @operationId Get Group Details
     * @authenticated
     */
    public function getGroupDetails(Group $id){
        try {
            $details = $this->_groupManageService->getGroupDetails($id, $this->per_page);
            return parent::sendResponse($details, 'Group Details retrived', 200);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }
    /**
     * @operationId Follow Group
     * @authenticated
     */
    public function followGroup(Request $request){
        $validator = Validator::make($request->all(), [
            'group_id' => 'required'
        ]);

        if ($validator->fails()) {
            return parent::sendError($validator->errors()->first(), $validator->errors(), 422);
        }
        
        try {
            $response = $this->_groupManageService->followGroup($request);
            return parent::sendResponse($response, "Group following", 200);     
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }
}
