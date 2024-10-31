<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Mail\SendForgotPasswordOtp;
use App\Models\Blog;
use App\Models\Cms;
use App\Models\ContactUsData;
use App\Models\Faq;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        try {

            return Inertia::render('Frontend/Home');

        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server Error');
        }
    }


    public function test()
    {
        return response()->json(['success'=>true, 'message'=>'get data successfully']);
    }

    public function login()
    {
        if(auth()->user() && auth()->user()->role_name == 'USER'){
            return to_route('frontend.profile');
        }
        return Inertia::render('Frontend/Auth/Login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials['active'] = 1;

        if (Auth::attempt($credentials) && Auth::user()->role_name == 'USER') {
            $request->session()->regenerate();
            session()->flash('success', 'You have successfully logged in!');
            return redirect()->intended('/profile');
        }

        return back()->withErrors([
            'password' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function signUp()
    {
        if(auth()->user() && auth()->user()->role_name == 'USER'){
            return to_route('frontend.profile');
        }
        return Inertia::render('Frontend/Auth/SignUp');
    }

    public function signUpProcess(Request $request)
    {
        $credentials = $request->validate([
            'first_name' => ['required', 'max:255'],
            'last_name' => ['nullable', 'max:255'],
            'email' => ['required', 'email', 'regex:/(.+)@(.+)\.(.+)/i', 'max:255', Rule::unique('users')],
            'phone' => ['nullable','numeric', Rule::unique('users'), 'digits_between:8,15' ],
            'contract_end_date' => ['nullable'],
            'password' => ['required', 'max:255', 'min:6'],
            'password_confirmation' => ['required', 'max:255', 'min:6', 'same:password'],
            'terms_and_conditions' => ['required'],
        ],[
            'first_name.required'=> 'The name field is required',
          ]);

        $user = new User();

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->contract_end_date = $request->contract_end_date;
        $user->password = $request->password;
        $user->save();

        $user->assignRole('USER');

        session()->flash('success', 'You have successfully registered!');
        return redirect()->route('frontend.login');

    }


    public function dashboard()
    {
        return Inertia::render('Frontend/Dashboard');
    }

    public function profile()
    {
        $user = auth()->user();
        return Inertia::render('Frontend/Profile',compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        $user = auth()->user();

        $credentials = request()->validate([
            'first_name' => 'required',
            'last_name' => 'nullable',
            'email' =>  'required|email:rfc,dns|unique:users,email,'.$user->id,
        ],[
            'first_name.required'=> 'The name field is required.',
          ],
        );

          $user->first_name = request()->first_name;
          $user->last_name = request()->last_name;
          $user->email = request()->email;

          if(request()->file('password'))
          {
            $user->password = request()->password;
          }

         /*  if(request()->file('profile_photo')){
              File::delete(storage_path('app/'.$user->profile_photo_path));
              $user->profile_photo_path = request()->file('profile_photo')->store('profile_photo');
            } */
          $user->save();

          session()->flash('success', 'Profile successfully updated');
          return redirect('/profile');
    }
    public function profilePasswordUpdate(Request $request)
    {
        $user = auth()->user();

        $credentials = request()->validate([
            'password' => ['required', 'max:255', 'min:6'],
        /*        'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password', */
          ],
       /*    [
            'confirm_password.same'=> 'New password & confirm password must be same'
          ] */
        );

        $user->password = request()->password;

        $user->save();

          session()->flash('success', 'Password has been updated successfully.');
          return redirect('/profile');
    }

    public function profileImageUpdate(Request $request)
    {

        $credentials = request()->validate([
            'photo' => ['required', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
          ],
        );

        $user = auth()->user();

         if($request->photo){
            // File::delete(storage_path('app/'.$user->profile_photo_path));
            $user->profile_photo_path = $request->photo->store('profile_photo');
          }

        $user->save();

          session()->flash('success', 'Profile image has been updated successfully.');
          return redirect('/profile');
    }
    public function myFavorite()
    {
        return Inertia::render('Frontend/MyFavorite');
    }



    public function logout()
    {
       Auth::logout();
       return redirect('/login');
    }


    public function forgotPassword(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'email'=>'required|email'
            ]);

            $token = rand(1000,9999);

            $get_user = User::where('email',$request->email)->first();

            if(!$get_user) {
                return back()->withErrors([
                    'email' => 'Please enter your registered email',
                ]);
            }

             DB::table('password_reset_tokens')->where('email',$request->email)->delete();


             DB::table('password_reset_tokens')->insert([
                'email'=>$request->email,
                'token'=>$token,
                'created_at'=>now(),
            ]);

            $data['code'] = $token;

            if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($request->email)->send(new SendForgotPasswordOtp($data));
            }

            session()->flash('success','OTP successfully send.');
            session()->put('forgot_password_email',$request->email);
            return redirect()->route('frontend.user.otpValidations');
        }

        return inertia('Frontend/Auth/ForgotPassword');
    }



    public function otpValidations(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'email'=>'required|email',
                'inputOTP.*'=>'required'
            ]);

            // dd($request->inputOTP);

            $otp = $request->inputOTP[0].$request->inputOTP[1].$request->inputOTP[2].$request->inputOTP[3];

            $get_otp = DB::table('password_reset_tokens')->where('token',$otp)->where('email',$request->email)->first();

            if(!$get_otp) {
                session()->flash('error','Please enter a valid OTP.');
                return back();
            }
            DB::table('password_reset_tokens')->where('email',$request->email)->delete();

            session()->flash('success','OTP successfully validate.');
            return to_route('frontend.user.resetPassword');

        }

         return inertia('Frontend/Auth/OtpValidation')->with('email',session()->get('forgot_password_email'));
    }


    public function resetPassword(Request $request)
    {

        if($request->isMethod('post')){

            $request->validate([
                'password'=>'required|min:8',
                'confirm_password'=>'required|same:password'
            ]);
            $user = User::where('email',session()->get('forgot_password_email'))->first();
            if($user){
                // dd(session()->get('forgot_password_email'),$request->password,$user);
                 $user->password = $request->password;
                 $user->save();
                 session()->flash('success','Password successfully changed.');
             }else{
                session()->flash('error','Something went wrong. Please try again.');
             }

             $request->session()->forget('forgot_password_email');
             if($user->role_name == 'SUPER-ADMIN'){
                return to_route('admin.login');
             }

             if($user->role_name == 'USER'){
                return to_route('frontend.login');
             }
        }

        return inertia('Frontend/Auth/ResetPassword');
    }

}
