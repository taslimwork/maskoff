<?php

namespace App\Interfaces;

interface UserInterface{
    public function _getAllUsers($search, $perPage);
    public function checkConnectionIsExists($request);
    public function _sendConnectRequest($request);
    public function _getUserConnectedList($request, $perPage);
    public function _getUserConnectedUserIds();
    public function connectionActionAttempt($request);
    public function getConnectionStatus($user);
    public function getUserConnections($user, $perPage);
}
