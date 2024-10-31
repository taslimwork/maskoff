<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\SendForgotPasswordOtp;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


/**
 * @tags Auth-management
 */
class AuthController extends BaseController
{
     /**
     * @operationId register
     * @unauthenticated
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "first_name" =>  "required|min:3",
            "last_name" =>  "required|min:3",
            "email" =>  "required|email|unique:users,email",
            "phone" => "required|numeric|min_digits:8|max_digits:15|unique:users,phone",
            'dob' => 'required|date|before:'.date('Y-m-d', strtotime('-18 years')),
            "username" =>  "required|min:3|unique:users,username",
            "password" =>  "required|min:8",
            "confirm_password"  =>  "required|min:8|same:password",
            'terms' => 'required|accepted'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->errors(), 422);
        }

        try {
            $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->dob = $request->dob;
            $user->username = $request->username;
            $user->password = $request->password;
            $user->accept_terms = $request->terms;
            $user->save();
            $user->assignRole('USER');

            $token =  $user->createToken('token')->plainTextToken;

            return response()->json(["status" => true, "message" => "User successfully created", "token" => $token, "data" => $user],200);
            // return response()->json([
            //     "success" => true,
            //     "token" => $token,
            //     "data" => $user,
            //     "message" => "User Register Successfully in successfully."
            // ], 200);
        } catch (\Exception $th) {
            Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }

    /**
     * @operationId login
     * @unauthenticated
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "username" =>  "required",
            "password" =>  "required",
            "device_token" =>  "nullable",
            "device_type" =>  "nullable"
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->errors(),422);
        }

        try {
            $loginType = filter_var($request->input('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $request->merge([
                $loginType => $request->input('username')
            ]);

            $credential = $request->only($loginType, 'password');
            $credential['active'] = 1;

            $isActiveUser = User::where($loginType, $request->input('username'))->first();
            if ($isActiveUser && $isActiveUser->active == 0) {
                return $this->sendError("Whoops! Account suspended", [], 422);
            }

            if (Auth::attempt($credential)) {
                $user =  User::find(Auth::id());
                $token =  $user->createToken('token')->plainTextToken;
                // $user->device_token = $request->device_token;
                // $user->device_type = $request->device_type;
                $user->save();
                return response()->json([
                    "success" => true,
                    "token" => $token,
                    "data" => $user,
                    "message" => "User logged in successfully."
                ], 200);
            } else {

                return $this->sendError('Whoops! Invalid Credential.', [],422);
                // return response()->json(["status" => false, "message" => "Whoops! Invalid Credential", "data" => []]);
            }
        } catch (\Exception $th) {
            Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
            return $this->sendServerError($th);
        }
    }


    /**
     * @operationId forgot-password
     * @unauthenticated
     */

     public function forgotPassword(Request $request)
     {
         $validator = Validator::make($request->all(), [
             "email" =>  "required|email|exists:users,email",
         ],[
            'email.email' => 'The entered email is invalid'
         ]);

         if ($validator->fails()) {
             return $this->sendError($validator->errors()->first(), $validator->errors(),422);
         }

         try {

             $get_user = User::where('email', $request->email)->first();

             /* if (!$get_user) {
                 $validator->addError('email', 'This email address is not exists.');
             } */
             if( $get_user)
             {
                 $otp = rand(1000, 9999);

                 $get_otp = DB::table('password_reset_tokens')->where('email', $request->email)->delete();

                 $create_token = DB::table('password_reset_tokens')->insert([
                     'email' => $request->email,
                     'token' => $otp,
                     'created_at' => now(),
                 ]);

                 $data['code'] = $otp;

                 if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                     Mail::to($request->email)->send(new SendForgotPasswordOtp($data));
                 }
                 // return response()->json(["status" => true, "message" => "OTP successfully send" , "data" => $data]);

                 return response()->json([
                     "success" => true,
                     "data" => $data,
                     "message" => "OTP successfully sent."
                 ], 200);
             }
             else{
                 return $this->sendServerError("This email address is not exists ");
             }

         } catch (\Exception $th) {
             Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
             return $this->sendServerError($th);
         }
     }

     /**
      * @operationId otp-verify
      * @unauthenticated
      */
     public function otpVerify(Request $request)
     {
         $validator = Validator::make($request->all(), [
             "email" =>  "required|email",
             "otp" =>  "required|numeric",
         ]);

         if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->errors(),422);
         }

         try {

             $get_otp = DB::table('password_reset_tokens')->where('token', $request->otp)->where('email', $request->email)->first();

             if($get_otp)
             {
                   /*   if (!$get_otp) {
                     return back()->withErrors([
                         'otp' => 'Invalid OTP',
                     ]);
                 } */
                 DB::table('password_reset_tokens')->where('email', $request->email)->delete();

                 $user = User::where('email', $request->email)->first();
                 if ($user) {
                    $token =  $user->createToken('token')->plainTextToken;
                    return response()->json(["success" => true, "message" => "OTP successfully validate.", "token" => $token, "data" => $user]);
                 }else {
                    return parent::sendError("Account not found", [], 422);
                 }


             }
             else{
                 return $this->sendError("Please enter correct OTP.", [], 422);
             }


         } catch (\Exception $th) {
             return $this->sendServerError($th);
         }
     }

     /**
      * @operationId Reset-Password
      */
     public function resetPassword(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'password' => 'required|min:8',
             'confirm_password' => 'required|min:8|same:password',
             'email' => 'required|email|exists:users,email'
         ]);

         if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->errors(),422);
         }

         try {

             $user = User::where('email', $request->email)->first();
             if (Hash::check($request->password, $user->password)) {
                 return $this->sendError('You have already used this password.');
             } else {

                 $user->password = $request->password;
                 $user->save();
                 return response()->json(["success" => true, "message" => "Your password changed successfully.", "data" => []]);
             }
         } catch (\Exception $e) {
             Log::info($e);
             return response()->json(["success" => false, "message" => "Server Error!"], 500);
         }
     }

      /**
      * @operationId change-Password
      */
     public function changePassword(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'old_password' => 'required|min:8',
             'new_password' => 'required|min:8',
             'confirm_password' => 'required|min:8|same:new_password'
         ]);

         if ($validator->fails()) {
             return $this->sendError($validator->errors()->first());
         }

         try {

             $user = User::find(Auth::id());

             if (!Hash::check($request->old_password, $user->password)) {
                 return $this->sendError('You have provide wrong old password.');
             }

             if (Hash::check($request->new_password, $user->password)) {
                 return $this->sendError('You have already used this password.');
             } else {

                 $user->password = $request->new_password;
                 $user->save();
                 return response()->json(["success" => true, "message" => "Your password changed successfully.", "data" => []]);
             }
         } catch (\Exception $e) {
             Log::info($e);
             return response()->json(["success" => false, "message" => "Server Error!"], 500);
         }
     }
      /**
      * @operationId Username suggestion
      */
    public function getUsernameSuggestion(Request $request) {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'nullable',
        ]);

        if ($validator->fails()) {
            return parent::sendError($validator->errors()->first(), $validator->errors(), 422);
        }

        try {
            $firstName =  strtolower(trim($request->first_name));
            $lastName =  strtolower(trim($request->last_name));
            $firstLast =  $firstName.$lastName;
            $lastFirst = $lastName.$firstName;
            $firstRandNum = $firstName. rand(1, 999);
            $lastRandNum = $lastName. rand(1, 999);
            $firstLastRandNum = $firstLast. rand(1, 999);
            $lastFirstRandNum = $lastFirst. rand(1, 999);
            $suggestion = [];
    
            $suggestArr = [$firstLast, $lastFirst, $firstRandNum, $lastRandNum, $firstLastRandNum, $lastFirstRandNum];
    
            while (count($suggestion) < 5) {
                $randUsername = array_rand($suggestArr);
                if (!in_array($suggestArr[$randUsername], $suggestion) && !User::where('username', $randUsername)->exists()) {
                    $suggestion[] = $suggestArr[$randUsername];
                }
            }

            return parent::sendResponse($suggestion, "Username Suggestions", 200);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }



}
