<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ContactUsData;
use App\Models\NewsletterData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        if(auth()->user() && auth()->user()->role_name == 'SUPER-ADMIN'){
            return to_route('admin.dashboard');
        }

        if(request()->isMethod('post')){
            $credentials = request()->validate([
              'email' => ['required', 'email'],
              'password' => ['required'],
          ]);

          if (Auth::attempt($credentials) && Auth::user()->role_name == 'SUPER-ADMIN') {
              request()->session()->regenerate();
              return redirect()->intended('/admin/dashboard');
          }

          return back()->withErrors([
              'email' => 'The provided credentials do not match our records.',
          ])->onlyInput('email');
        }

        return Inertia::render('common/SuperAdminLogin');
    }



    public function dashboard()
    {
        $data['active_user'] = User::role('USER')->where('active',1)->with('roles')->count();
        $data['inactive_user'] = User::role('USER')->where('active',0)->count();
        $dataTotal = User::select(DB::raw('count(id) as count'), DB::raw("MONTH(created_at)  as month"))->role('USER')->groupBy('month')->orderBy('month')->get()->toArray();
        $models = collect($dataTotal);
        $data['months'] = collect(range(0, 11))->map(
          function ($month) use ($models) {
            $match = $models->firstWhere('month', $month);
            return $match ? $match['count'] : 0;
          }
        );

        return Inertia::render('Admin/Dashboard',compact('data'));
    }


    function adminProfile()
    {
        $user = auth()->user();

        if(request()->isMethod('post')){

            $credentials = request()->validate([
              'first_name' => 'required',
              'last_name' => 'required',
              'email' =>  'required|email:rfc,dns|unique:users,email,'.$user->id,
              // 'profile_photo' => 'required',
            ]);

            $user->first_name = request()->first_name;
            $user->last_name = request()->last_name;
            $user->email = request()->email;

            if(request()->file('profile_photo')){
                File::delete(storage_path('app/'.$user->profile_photo_path));
                $user->profile_photo_path = request()->file('profile_photo')->store('profile_photo');
              }
            $user->save();

            session()->flash('success', 'Profile successfully updated');
            return redirect('admin/admin-profile');
          }

          $user->profile_photo = $user->profile_photo_path ? url()->route('image', ['path' => $user->profile_photo_path]) : null;

        return Inertia::render('Admin/AdminProfile',compact('user'));
    }


    public function adminChangePassword()
    {
        $credentials = request()->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
          ],
          [
            'old_password.required' => 'The current password field is required.',
            'confirm_password.same'=> 'New password & confirm password must be same'
          ]);


          $user = auth()->user();
           if (!Hash::check(request()->old_password, $user->password)) {
             return to_route('admin.profile')->with('error', "The current Password doesn't match");
            }
              $user->password = request()->new_password;
              $user->save();
              Auth::logout();

        session()->flash('success', 'Password changed successfully');
        return to_route('admin.login');

    }


    public function logout()
    {
       Auth::logout();
       return to_route('admin.login');
    }

    /* Contact us information list */
    public function contactUsDataList()
    {
        try {

            $filters = request()->all('name','email','phone');

            $contactUsDataList = ContactUsData::filter(request()->only('name','email','phone'))
                ->ordering(request()->only('fieldName','shortBy'))
                ->orderBy('id','desc')
                ->paginate(request()->perPage ?? $this->per_page)->withQueryString();
            return Inertia::render('Admin/contact-us/List',compact('contactUsDataList','filters'));
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server Error');
        }
    }
    public function contactUsDataDestroy($id)
    {
        try {
            ContactUsData::where('id',$id)->delete();
            session()->flash('success', 'Contact us data has been deleted successfully');
            return back();
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server Error');
        }
    }
    /* Newsletter us information list */
    public function newsletterDataList()
    {
        try {

            $filters = request()->all('name','email','phone');

            $newsletterDataList = NewsletterData::filter(request()->only('name','email','phone'))
                ->ordering(request()->only('fieldName','shortBy'))
                ->orderBy('id','desc')
                ->paginate(request()->perPage ?? $this->per_page)->withQueryString();
            return Inertia::render('Admin/newsletter/List',compact('newsletterDataList','filters'));
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server Error');
        }
    }
    public function newsletterDataDestroy($id)
    {
        try {
            NewsletterData::where('id',$id)->delete();
            session()->flash('success', 'Newsletter data has been deleted successfully');
            return back();
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server Error');
        }
    }
}
