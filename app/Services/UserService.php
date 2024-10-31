<?php

namespace App\Services;

use App\Interfaces\UserInterface;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserService{

    public $_userInterface, $_postService;

    public function __construct(UserInterface $userInterface, PostService $postService){
        $this->_userInterface = $userInterface;
        $this->_postService = $postService;
    }

    public function getAllUsers($search, $perPage){
        return $this->_userInterface->_getAllUsers($search, $perPage);
    }

    public function checkConnectionIsExists($request){
        return $this->_userInterface->checkConnectionIsExists($request);
    }

    public function sendConnectRequest($request){

        return $this->_userInterface->_sendConnectRequest($request);
    }

    public function getUserConnectionList($request, $perPage){
        return $this->_userInterface->_getUserConnectedList($request, $perPage);
    }

    public function connectionActionAttempt($request){
        return $this->_userInterface->connectionActionAttempt($request);
    }

    public function getConnectionStatus($user){
        $authUser = User::find(Auth::id());
        $connectStatus = $this->_userInterface->getConnectionStatus($user);
        
        if ($connectStatus == 1) {
            $connection_status = 'Connected';
        }else if ($connectStatus == 2) {
            $connection_status = 'Rejected';
        }else if ($connectStatus == 0) {
            $connection_status = 'Connection send';
        }
        return $connection_status;
    }

    public function getUserDetails($user, $perPage){
        $userDetails = $user->only('full_name', 'username', 'profile_photo_url', 'id');
        $userDetails['connection_status'] = $this->getConnectionStatus($user);
        $userDetails['connections'] = $this->_userInterface->getUserConnections($user, $perPage);
        $userDetails['hash_tags'] = $user->hashTags;
        $userDetails['posts'] = $this->_postService->myPost($user->id);
        $userDetails['interests'] = $this->_postService->getInterestsPosts('interest_page', $user->id);
        $userDetails['content'] = $this->_postService->getContent();
        return $userDetails;
    }
}