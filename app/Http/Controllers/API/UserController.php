<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use App\Services\PostService;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
/**
 * @tags User Management
 */
class UserController extends BaseController
{

    public $_userService, $_postService;

    public function __construct(UserService $userService, PostService $postService){
        $this->_userService = $userService;
        $this->_postService = $postService;
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array {
        return [
            'dob.required' => 'The date of birth field is required.',
            'dob.date' => 'The date of birth field must be a valid date.',
            'dob.before' => 'The date of birth field must be a date before '.date('Y-m-d', strtotime('-18 years')).'.',
        ];
    }

     /**
     * @operationId My Profile
     * @authenticated
     */
    public function profile()
    {
        try {
            $user = auth()->user();
            $user['hash_tags'] = $user->hashTags;
            $user['posts'] = $this->_postService->myPost($user->id);
            $user['interests'] = $this->_postService->getInterestsPosts('interest_page', $user->id);
            $user['content'] = $this->_postService->getContent();
            return response()->json(["status" => true, "message" => "Profile retrived successfully", "data" => $user]);

        } catch (\Throwable $th) {
            return $this->sendServerError($th);
        }
    }
    /**
     * @operationId Update-Profile
     * @authenticated
     */
    public function updateProfile(Request $request){
        $validator = Validator::make($request->all(), [
                "first_name" =>  "required|min:3",
                "last_name" =>  "required|min:3",
                "email" =>  "required|email",
                "phone" => "required|numeric|min_digits:8|max_digits:15",
                'dob' => 'required|date|date_format:Y-m-d|before:'.date('Y-m-d', strtotime('-18 years')),
                "username" =>  "required|min:3",
                "profile_photo" => "nullable|image|mimes:jpg,png,jpeg,gif",
                "hashtag" => "required|array",
            ]);

        if ($validator->fails()) {
            return parent::sendError($validator->errors()->first(), $validator->errors(), 422);
        }

        $validator = Validator::make($request->all(), [
            "email" =>  "unique:users,email,".auth()->user()->id,
            "username" =>  "unique:users,username,".auth()->user()->id,
            "phone" => "unique:users,phone,".auth()->user()->id,
        ]);

        if ($validator->fails()) {
            return parent::sendError($validator->errors()->first(), $validator->errors(), 422);
        }

        try {

            $user = User::find(Auth::id());

            if ($request->profile_photo) {
                if ($user->profile_photo_path) {
                    File::delete(storage_path('app/public/'.$user->profile_photo_path));
                }
                $user->profile_photo_path = request()->file('profile_photo')->store('profile_photo');
            }

            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->dob = $request->dob;
            $user->save();

            $user->hashTags()->delete();
            foreach ($request->hashtag as $tag) {
                $user->hashTags()->create([
                    'tag_title' => $tag
                ]);
            }
            $user['hash_tags'] = $user->hashTags;
            return parent::sendResponse($user, 'Profile updated successfully');
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }


    /**
     * @operationId Delete-Account
     * @authenticated
     */
    public function deleteAccount(){
        try {
            auth()->user()->tokens->each(function ($token, $key) {
                $token->delete();
            });
            $user = User::find(Auth::id());
            $user->delete();
            return parent::sendResponse([], "Account deleted successfully");
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }

    }
    /**
     * @operationId Contact-Us
     * @authenticated
     */
    public function contactUs(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required|exists:users,username',
            'email' => 'required|email|exists:users,email',
            'subject' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return parent::sendError($validator->errors()->first(), $validator->errors(), 422);
        }

        try {
            $contact = new ContactUs();
            $contact->user_id = Auth::id();
            $contact->username = $request->username;
            $contact->email = $request->email;
            $contact->subject = $request->subject;
            $contact->description = $request->description;
            $contact->save();

            return parent::sendResponse($contact, "Thanks! We'll work on your feedback");
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }

    }

    /**
     * @operationId logout
     * @authenticated
     */
    public function logout(Request $request)
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return $this->sendResponse([], 'Logout successfully.');
    }

     /**
     * @operationId All users
     * @authenticated
     */
    public function getAllUsers(Request $request) {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string|min:3',
        ]);

        if ($validator->fails()) {
            return parent::sendError($validator->errors()->first(), $validator->errors(), 422);
        }

        try {
            $users = $this->_userService->getAllUsers($request->search, $this->per_page);
            return parent::sendResponse($users, 'Users retrived', 200);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }

    /**
     * @operationId Send connect request
     * @authenticated
     */
    public function sendConnectRequest(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);

        if ($validator->fails()) {
            return parent::sendError($validator->errors()->first(), $validator->errors(), 422);
        }

        try {
            $isExists = $this->_userService->checkConnectionIsExists($request);
            if ($isExists) {
                return parent::sendError("Connection request already send", [], 422);
            }
            $response = $this->_userService->sendConnectRequest($request);
            return parent::sendResponse($response, 'Connection request sent', 200);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }
    /**
     * @operationId Get user connected list
     * @authenticated
     */
    public function getUserConnectionList(Request $request){
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|min:3'
        ]);

        if ($validator->fails()) {
            return parent::sendError($validator->errors()->first(), $validator->errors(), 422);
        }

        try {
            $response = $this->_userService->getUserConnectionList($request, $this->per_page);
            return parent::sendResponse($response, 'Connection list retrived successfully', 200);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }

    /**
     * @operationId Get user connection Action Attempt
     * @authenticated
     */
    public function connectionActionAttempt(Request $request){
        $validator = Validator::make($request->all(), [
            'connection_id' => 'required',
            'action' => 'required'
        ]);

        if ($validator->fails()) {
            return parent::sendError($validator->errors()->first(), $validator->errors(), 422);
        }

        try {
            $response = $this->_userService->connectionActionAttempt($request);
            if ($request->action == 1) {
                $msg = 'Connection accepted';
            }
            if ($request->action == 2) {
                $msg = 'Connection rejected';
            }
            return parent::sendResponse($response, $msg, 200);
            
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }

    /**
     * @operationId Get user Details
     * @authenticated
     */
    public function getUserDetails(User $id){
        try {
            $user = $this->_userService->getUserDetails($id, $this->per_page);
            return parent::sendResponse($user, "User data retrived.", 200);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }
}
