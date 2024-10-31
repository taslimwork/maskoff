<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersExport;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Maatwebsite\Excel\Facades\Excel;


class UserController extends Controller
{
    public function userlist()
    {
      try {

        $users = User::filter(Request::only('name','email','active'))->role('USER')->ordering(Request::only('fieldName','shortBy'))->orderBy('id','desc')->paginate(request()->perPage ?? $this->per_page)->withQueryString()
        ->through(fn ($user) => [
          'id' => $user->id,
          'full_name' => $user->full_name,
          'email' => $user->email,
          'phone' => $user->phone,
          'active' => $user->active,
        //   'profile_photo' => $user->profile_photo_path ? URL::route('image', ['path' => $user->profile_photo_path, 'w' => 40, 'h' => 40, 'fit' => 'crop']) : null,
          'profile_photo' => $user->profile_photo_url,
          'deleted_at' => $user->deleted_at,
        ]);

        $filters = Request::all('name','email','phone','active');

        return Inertia::render('Admin/user/List', compact('filters','users'));

      } catch (\Exception $e) {
          Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
          return back()->with('error','Server error');
      }

    }

    public function createUser()
    {
      if(request()->isMethod('post')){

       request()->validate([
            'first_name' => 'required|max:40',
            'last_name' => 'required|max:40',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'username' => 'required|min:3|regex:/^[a-zA-Z0-9]+$/|unique:users,username',
            'phone' => 'required|unique:users,phone|min_digits:8|max_digits:15',
            'password' => 'required|min:6',
            'dob' => 'required|before:'.date('Y-m-d', strtotime('-18 years')),
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,gif',
            'status' => 'required',
        ],[
          'dob.required' => 'The DOB field is required.',
          'dob.before' => 'The DOB field must be a date before '. date('Y-m-d', strtotime('-18 years')) .'.',
          'username.regex' => 'The username field must be contain alphabate and number only.',
        ]);


        $user = new User;
        $user->first_name = request()->first_name;
        $user->last_name = request()->last_name;
        $user->email = request()->email;
        $user->username = request()->username;
        $user->phone = request()->phone;
        $user->dob = date('Y-m-d', strtotime(request()->dob));
        $user->password = request()->password;
        $user->active = request()->status ?? 1;
        $user->profile_photo_path = Request::file('profile_photo') ? Request::file('profile_photo')->store('profile_photo') : null;
        $user->save();
        $user->assignRole('USER');
        session()->flash('success', 'User successfully created');
        return redirect()->route('admin.users');
      }

      return Inertia::render('Admin/user/CreateEdit');
    }



    public function editUser(User $user)
    {
      if(request()->isMethod('post')){

        $credentials = request()->validate([
          'first_name' => 'required|max:40',
          'last_name' => 'required|max:40',
          'username' => 'required|min:3|regex:/^[a-zA-Z0-9]+$/|unique:users,username,'.$user->id,
          'email' =>  'required|email:rfc,dns|unique:users,email,'.$user->id,
          'phone' => 'nullable|numeric|min_digits:8|max_digits:15|unique:users,phone,'.$user->id,
          'dob' => 'required|before:'.date('Y-m-d', strtotime('-18 years')),
          'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,gif',
          'status' => 'required',
        ],[
          'dob.required' => 'The DOB field is required.',
          'dob.before' => 'The DOB field must be a date before '. date('Y-m-d', strtotime('-18 years')) .'.',
          'username.regex' => 'The username field must be contain alphabate and number only.',
        ]);

        $user->first_name = request()->first_name;
        $user->last_name = request()->last_name;
        $user->email = request()->email;
        $user->username = request()->username;
        $user->phone = request()->phone;
        $user->dob = date('Y-m-d', strtotime(request()->dob));
        $user->password = request()->password;
        $user->active = request()->status ?? 1;
        if(Request::file('profile_photo')){
          File::delete(storage_path('app/'.$user->profile_photo_path));
          $user->profile_photo_path = Request::file('profile_photo')->store('profile_photo');
        }
        $user->save();

        session()->flash('success', 'User successfully updated');
        return redirect()->route('admin.users');
      }

       $user->profile_photo = $user->profile_photo_path ? URL::route('image', [
        'path' => $user->profile_photo_path ]) : null;


      return Inertia::render('Admin/user/CreateEdit',compact('user'));
    }

    public function deleteUser(User $user)
    {
      try{
          File::delete(storage_path('app/'.$user->profile_photo_path));
          $user->delete();
          session()->flash('success', 'User successfully deleted');
          return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server error');
        }
    }

    public function changeUserStatus(User $user)
    {
      try{
          $user->active = ($user->active == 1) ? 0 : 1 ;
          $user->save();
          session()->flash('success', 'User status successfully changed');
          return back();
        } catch (\Exception $e) {
          Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
          return back()->with('error','Server error');
      }
    }

    public function exportData(Request $request)
    {
        try{

            $filterData = json_decode(request()->filter, true);
            if($filterData )
            {
                request()->merge($filterData );
                unset(request()['filter']);
            }

            $users = User::filter(request()->only('name','email','active'))->role('USER')->ordering(request()->only('fieldName','shortBy'))->orderBy('id','desc')->get();

            if($users->isNotEmpty())
            {
                foreach ($users as $key => $item) {

                    $data[] = [
                        // "id" => $item->id,
                        "SL"=> $key+1,
                        "name" => $item->full_name,
                        "email" => $item->email,
                        // "phone" => $item->phone,
                    ];
                }
                return Excel::download(new UsersExport($data), 'User-statistics.xlsx');
            }
            else{
                return back();
            }

          } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server error');
        }
    }
}
