<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupMembers;
use App\Models\GroupType;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class GroupController extends Controller
{
    public function groupTypeList(){
        try {

            $groupTypes = GroupType::filter(Request::only('type', 'status'))->ordering(Request::only('fieldName','shortBy'))->orderBy('id','desc')->paginate(request()->perPage ?? $this->per_page)->withQueryString()
            ->through(fn ($groupType) => [
              'id' => $groupType->id,
              'type' => $groupType->type,
              'status' => $groupType->status,
            ]);
    
            $filters = Request::all('type', 'status');
    
            return Inertia::render('Admin/GroupType/GroupTypeList', compact('filters','groupTypes'));
    
          } catch (\Exception $e) {
              Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
              return back()->with('error','Server error');
          }
    }

    public function groupTypeCreate() {
        if (request()->isMethod('post')) {
            // dd(request()->all());
            request()->validate([
                'type' => 'required|min:3',
                'status' => 'required',
            ],[
                'type.required' => 'The group type field is required.',
                'type.min' => 'The group type field must be at least 3 characters.',
            ]);

            $groupType = new GroupType();
            $groupType->type = request()->type;
            $groupType->status = request()->status;
            $groupType->save();
            return redirect()->route('admin.groupTypes')->with('success', 'Group type has been created successfully.');
        }

        return Inertia::render('Admin/GroupType/CreateEditType');
    }

    public function groupTypeEdit(GroupType $groupType) {
        if (request()->isMethod('post')) {
            request()->validate([
                'type' => 'required|min:3',
                'status' => 'required',
            ]);

            $groupType->type = request()->type;
            $groupType->status = request()->status;
            $groupType->save();
            return redirect()->route('admin.groupTypes')->with('success', 'Group type has been updated successfully.');
        }

        return Inertia::render('Admin/GroupType/CreateEditType', compact('groupType'));
    }

    public function changeGroupTypeStatus(GroupType $groupType) {
        try {
            $groupType->status = $groupType->status == 1 ? 0 : 1;
            $groupType->save();
            session()->flash('success', 'Group type status has been updated successfully.');
            return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server error');
        }
    }

    public function deleteGroupType(GroupType $groupType) {
        try {
            $groupType->group()->delete();
            $groupType->delete();
            return redirect()->route('admin.groupTypes')->with('success', 'Group Type has been deleted successfully.');
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server error');
        }
    }

    public function groupList(){
        try {
            $groups = Group::filter(Request::only('name','moto', 'group_type', 'creator', 'status'))
                    ->ordering(Request::only('fieldName','shortBy', 'rel'))
                    ->orderBy('id','desc')
                    ->paginate(request()->perPage ?? $this->per_page)
                    ->withQueryString()
            ->through(fn ($group) => [
              'id' => $group->id,
              'name' => $group->name,
              'type' => $group->groupType->type,
              'created_by' => $group->groupCreator->full_name,
              'moto' => $group->moto,
              'description' => $group->description,
              'status' => $group->status,
              'group_logo' => $group->group_logo_url,
              'deleted_at' => $group->deleted_at,
            ]);
    
            $filters = Request::all('name','moto', 'group_type', 'creator', 'status');
    
            return Inertia::render('Admin/Group/List', compact('filters','groups'));
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server error');
        }
    }

    public function changeGroupStatus(Group $group){
        try {
            $group->status = $group->status == 1 ? 0 : 1;
            $group->save();
            session()->flash('success', 'Group status has been updated.');
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server error');
        }
    }

    public function groupMembers(Group $group){
        try {
            $members = GroupMembers::where('group_id', $group->id)->filter(Request::only('name', 'username', 'email', 'role', 'status'))->ordering(Request::only('fieldName','shortBy'))->orderBy('id','desc')->paginate(request()->perPage ?? $this->per_page)->withQueryString()
            ->through(fn ($groupMember) => [
              'id' => $groupMember->id,
              'groupId' => $groupMember->group_id,
              'name' => $groupMember->user->full_name,
              'username' => $groupMember->user->username,
              'profile_photo_url' => $groupMember->user->profile_photo_url,
              'email' => $groupMember->user->email,
              'role' => $groupMember->is_admin == 1 ? 'Group Admin' : 'Member',
              'status' => $groupMember->status,
              'created_at' => $groupMember->created_at,
              'user_id' => $groupMember->user_id,
            ]);
    
            $filters = Request::all('name', 'username', 'email', 'role', 'status');
            return Inertia::render('Admin/Group/Members', compact('members', 'filters', 'group'));
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server error');
        }
    }

    public function groupMemberChangeStatus(){
        // dd(request()->all());
        $findMember = GroupMembers::where('group_id', request()->group)->where('user_id', request()->member)->first();
        $findMember->status = $findMember->status == 1 ? 0 : 1;
        $findMember->save();
        return redirect()->route('admin.groupMembers', $findMember->group_id)->with('success', 'Member status has been changed successfully.');
    }

    public function groupMemberDelete(){
        try {
            $findMember = GroupMembers::where('group_id', request()->group)->where('user_id', request()->member)->delete();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server error');
        }
    }
}
