<?php

namespace App\Repositories;

use App\Interfaces\UserInterface;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserRepository implements UserInterface{

    public function _getAllUsers($search, $perPage){
        return User::role('USER')->where('id', "!=", auth()->id())
        ->when($search ?? null, function($query)use($search){
            $query->where(function($q)use($search){
                $q->where('username', 'like', '%' . trim($search). '%')
                    ->orWhereRaw("CONCAT(first_name, isNull(last_name)) LIKE '%" . trim($search). "%'");
            });
        })
        ->paginate($perPage)
        ->through(function ($user) {
            $connection = Invitation::where('model_type', 'App\Models\User')->where('action', '1')
                ->where(function($qry)use($user){
                    $qry->where('sender', $user->id)->orWhere('reciver', $user->id);
                })
                ->value('action');
            $isConnect = $connection == 1 ? true : false;

            $requestConn = Invitation::where('model_type', 'App\Models\User')
                ->where(function($qry)use($user){
                    $qry->where('sender', $user->id)->orWhere('reciver', $user->id);
                })
                ->value('action');
            $isReqested = $requestConn == '0' ? true : false;

            return [
                'user_id' => $user->id,
                'username' => $user->username,
                'full_name' => $user->full_name,
                'isConnect' => $isConnect,
                'isReqested' => $isReqested,
                'profile_photo_url' => $user->profile_photo_url,
            ];
        });
    }

    public function checkConnectionIsExists($request){
        $connection = Invitation::where('sender', Auth::id())->where('type', 1)->where('reciver', $request->user_id)->exists();
        if ($connection) {
            return true;
        }else {
            return false;
        }
    }

    public function _sendConnectRequest($request){
        $user = User::find(Auth::id());
        $connect = $user->invitations()->create([
            'type' => 1,
            'sender' => Auth::id(),
            'reciver' => $request->user_id,
        ]);

        $response =  $connect->recivePerson->only('id', 'first_name', 'last_name', 'username', 'profile_photo_url');
        $response['connection_id'] = $connect->id;
        return $response;
    }

    public function _getUserConnectedList($request, $perPage){
        $user = User::find(Auth::id());
        $search = $request->search;
        
        // $user->invitations()->where('type', 1)
        // ->when($search ?? null, function($query)use($search){
        //     $query->whereHas('recivePerson', function($qry)use($search){
        //         $qry->where('username', "LIKE", "%" . trim($search) . "%")
        //         ->orWhereRaw("CONCAT(first_name, isNull(last_name)) LIKE '%" . trim($search). "%'");
        //     });
        // })
        $users = Invitation::where('model_type', 'App\Models\User')
        ->where('type', 1)->where('action', '1')
        ->when($search ?? null, function($query)use($search){
            $query->where(function($q)use($search){
                $q->whereHas('recivePerson', function($qry)use($search){
                    $qry->where('username', "LIKE", "%" . trim($search) . "%")
                    ->orWhereRaw("CONCAT(first_name, isNull(last_name)) LIKE '%" . trim($search). "%'");
                })
                ->orWhereHas('sendPerson', function($qry)use($search){
                    $qry->where('username', "LIKE", "%" . trim($search) . "%")
                    ->orWhereRaw("CONCAT(first_name, isNull(last_name)) LIKE '%" . trim($search). "%'");
                });
            });
        })
        ->where(function($query){
            $query->where('sender', Auth::id())->orWhere('reciver', Auth::id());
        })
        ->paginate($perPage)
        ->through(function($connect){
            if ($connect->reciver == Auth::id()) {
                $data = [
                    'user_id' => $connect->sendPerson->id,
                    'username' => $connect->sendPerson->username,
                    'full_name' => $connect->sendPerson->full_name,
                    'profile_photo_url' => $connect->sendPerson->profile_photo_url,
                    'connection_id' => $connect->id,
                    'action' => $connect->action,
                    'connection_status' => $connect->action == 1 ? 'Connected': ($connect->action == 2 ? 'Rejected' : 'No action')
                ];
            }
            if ($connect->sender == Auth::id()) {
                $data = [
                    'user_id' => $connect->recivePerson->id,
                    'username' => $connect->recivePerson->username,
                    'full_name' => $connect->recivePerson->full_name,
                    'profile_photo_url' => $connect->recivePerson->profile_photo_url,
                    'connection_id' => $connect->id,
                    'action' => $connect->action,
                    'connection_status' => $connect->action == 1 ? 'Connected': ($connect->action == 2 ? 'Rejected' : 'No action')
                ];
            }
            return $data;
        });
        // ->paginate(10)
        // ->through(function ($user) {
        //     return [
        //         'user_id' => $user->recivePerson->id,
        //         'username' => $user->recivePerson->username,
        //         'full_name' => $user->recivePerson->full_name,
        //         'profile_photo_url' => $user->recivePerson->profile_photo_url,
        //         'connection_id' => $user->id
        //     ];
        // });
        return $users;  
    }

    public function _getUserConnectedUserIds(){
        return Invitation::where('model_type', 'App\Models\User')
        ->where('type', 1)->where('action', '1')
        ->where(function($query){
            $query->where('sender', Auth::id())->orWhere('reciver', Auth::id());
        })
        ->get()
        ->map(function($conn){
            if ($conn->reciver == Auth::id()) {
                return $conn->sendPerson->id;
            }
            if ($conn->sender == Auth::id()) {
                return $conn->recivePerson->id;
            }
        });
    }

    public function connectionActionAttempt($request){
        $connection = Invitation::find($request->connection_id);
        $connection->action = $request->action;
        $connection->action_attempt_by = Auth::id();
        $connection->save();
        
        $response['action'] = $connection->action;
        $response['action_status'] = ($connection->action == 1) ? 'Connected' : 'Rejected';
        $response['sender'] = $connection->sendPerson->only('full_name', 'username', 'profile_photo_url', 'id as user_id');
        $response['reciver'] = $connection->recivePerson->only('full_name', 'username', 'profile_photo_url', 'id as user_id');
        $response['actionAttemptBy'] = $connection->actionAttemptBy->only('full_name', 'username', 'profile_photo_url', 'id as user_id');
        return $response;
    }

    public function getConnectionStatus($user){
        return Invitation::where('model_type', 'App\Models\User')
        ->where(function($query)use($user){
            $query->where('sender', $user->id)->orWhere('reciver', $user->id);
        })->value('action');
    }

    public function getUserConnections($user, $perPage){
        return Invitation::where('model_type', 'App\Models\User')
                ->where('action', '1')
                ->where(function($query)use($user){
                    $query->where('sender', $user->id)->orWhere('reciver', $user->id);
                })
                ->paginate($perPage)
                ->through(function($connect) use($user){
                    if ($connect->reciver == $user->id) {
                        $data = $connect->sendPerson->only('full_name', 'username', 'profile_photo_url', 'id');
                    }
                    if ($connect->sender == $user->id) {
                        $data = $connect->recivePerson->only('full_name', 'username', 'profile_photo_url', 'id');
                    }
                    return $data;
                });
    }
}