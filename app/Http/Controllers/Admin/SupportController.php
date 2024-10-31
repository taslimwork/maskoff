<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class SupportController extends Controller
{
    public function contactUsList(){
        try {
            $supports= ContactUs::filter(Request::only('username','email','subject','description','status'))
            ->ordering(Request::only('fieldName','shortBy'))
            ->orderBy('id','desc')
            ->paginate(request()->perPage ?? $this->per_page)
            ->withQueryString()
            ->through(fn ($support) => [
                'id' => $support->id,
                'full_name' => $support->user->full_name,
                'profile_photo' => $support->user->profile_photo_url,
                'username' => $support->username,
                'email' => $support->email,
                'subject' => $support->subject,
                'description' => $support->description,
                'status' => $support->status,
            ]);

            $filters = Request::all('username','email','subject','description','status');
            return Inertia::render('Admin/Support/List', compact('filters','supports'));

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server error');
        }
    }

    public function changeContactUsStatus(ContactUs $support) {
        try {
            $support->status = $support->status == 1 ? 0 : 1;
            $support->save();
            session()->flash('success', 'Support status has been updated successfully.');
            return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server error');
        }
    }
}
