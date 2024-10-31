<?php

/**
 * --------------------------------------------------------------
 *  Namespace
 * --------------------------------------------------------------
 */
namespace App\Interfaces;


interface GroupManageInterface{
    public function _getGroupType();
    public function _createGroup($group, $request);
    public function _joinGroup($groupId, $userId);
    public function _joinGroupAsMember($gruopId, $userId);
    public function _assignGroupAdmin($groupId, $userId);
    public function _getAllGroupList();
    public function _sendInvitation($inviteRequest);
    public function _invitationActionAttempt($request);
    public function _getGroup($id);
    public function _getAllGroupJoinRequest($group, $search);
}
