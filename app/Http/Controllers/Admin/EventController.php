<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Event;
use App\Models\EventType;
use App\Exports\UsersExport;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Validation\ValidationException;


class EventController extends Controller
{

    public function eventList(){

      try {
        $events= Event::filter(Request::only('name','organizer_name','event_date','event_time','active'))->ordering(Request::only('fieldName','shortBy'))->orderBy('id','desc')->paginate(request()->perPage ?? $this->per_page)->withQueryString()
        ->through(fn ($event) => [
          'id' => $event->id,
          'name' => $event->name,
          'organizer_name' => $event->organizer_name,
          'event_date' => $event->event_date,
          'event_time' => $event->event_time,
          'active' => $event->active,
        ]);

        $filters = Request::all('name','organizer_name','event_date','event_time','active');

        return Inertia::render('Admin/event/List', compact('filters','events'));

      } catch (\Exception $e) {
          Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
          return back()->with('error','Server error');
      }
    }

    public function createEvent()
    {
      if(request()->isMethod('post')){

       request()->validate([
            'name' => 'required|string|max:255',
            'event_type_id' => 'required|exists:event_types,id',
            'organizer_name' => 'nullable|string|max:255',
            'event_date' => 'required|date|date_format:Y-m-d|after:yesterday',
            'event_time' => 'required',
            'event_location' => 'required|string|max:2024',
            'description' => 'required|string',
            'event_features' => 'nullable|string',
            'sponsor_information.*.sponsor_name' => 'required',
            "image_file" => 'nullable|mimes:jpg,jpeg,png',
            'status' => 'required',
        ],[
          'event_type_id.required' => 'The event type field is required.',
          'sponsor_information.*.sponsor_name' => 'The sponser field is required.'
        ]);

        $user = auth()->user();
        $event = new Event();
        $event->user_id = $user->id;
        $event->name =  request()->name;
        $event->event_type_id =  request()->event_type_id;
        $event->organizer_name =  request()->organizer_name;
        $event->event_date =  request()->event_date;
        $event->event_time =  request()->event_time;
        $event->event_location =  request()->event_location;
        $event->description =  request()->description;
        $event->event_features =  request()->event_features;
        $event->sponsor_information = ( request()->sponsor_information != '') ? json_encode( request()->sponsor_information): json_encode([]);
        $event->image = Request::file('image_file') ? Request::file('image_file')->store('image_file') : null;
        $event->active = request()->status ?? 1;
        $event->save();
        session()->flash('success', 'Event successfully created');
        return redirect()->route('admin.events');
      }
      $event_types = EventType::select('name','id')->where('active', 1)->get();

      return Inertia::render('Admin/event/CreateEdit',compact('event_types'));
    }



    public function editEvent(Event $event)
    {
      if(request()->isMethod('post')){
        // try{
          // dd(request()->all());
          $credentials = request()->validate([
            'name' => 'required|string|max:255',
            'event_type_id' => 'required|exists:event_types,id',
            'organizer_name' => 'nullable|string|max:255',
            'event_date' => 'required|date|after:yesterday',
            'event_time' => 'required',
            'event_location' => 'required|string|max:2024',
            'description' => 'required|string',
            'event_features' => 'nullable|string',
            'sponsor_information.*.sponsor_name' => 'required',
            "image_file" => 'nullable|mimes:jpg,jpeg,png',
            'status' => 'required',
          ],[
            'event_type_id.required' => 'The event type field is required.',
            'sponsor_information.*.sponsor_name' => 'The sponser field is required.'
          ]);

        $event->name =  request()->name;
        $event->event_type_id =  request()->event_type_id;
        $event->organizer_name =  request()->organizer_name;
        $event->event_date =  date('Y-m-d', strtotime(request()->event_date));
        $event->event_time =  request()->event_time;
        $event->event_location =  request()->event_location;
        $event->description =  request()->description;
        $event->event_features =  request()->event_features;
        $event->sponsor_information = ( request()->sponsor_information != '') ? json_encode( request()->sponsor_information): json_encode([]);
        $event->image = Request::file('image_file') ? Request::file('image_file')->store('image_file') : null;
        $event->active = request()->status ?? 1;
        $event->save();
        if(Request::file('image_file')){
          File::delete(storage_path('app/'.$event->image));
          $event->image = Request::file('image_file')->store('image_file');
        }
        $event->save();

        session()->flash('success', 'Event successfully updated');
        return redirect()->route('admin.events');
      }

       $event->image_file = $event->image ? URL::route('image', [
        'path' => $event->image ]) : null;

      $event_types = EventType::select('name','id')->where('active', 1)->get();
      $sponsor_information = json_decode($event->sponsor_information);

      return Inertia::render('Admin/event/CreateEdit',compact('event','event_types','sponsor_information'));
    }

    public function deleteEvent(Event $event)
    {
      try{
          File::delete(storage_path('app/'.$event->image));
          $event->delete();
          session()->flash('success', 'Event successfully deleted');
          return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server error');
        }
    }

    public function changeEventStatus(Event $event)
    {
      try{
          $event->active = ($event->active == 1) ? 0 : 1 ;
          $event->save();
          session()->flash('success', 'Event status successfully changed');
          return back();
        } catch (\Exception $e) {
          Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
          return back()->with('error','Server error');
      }
    }

    public function eventTypeList() {
      try {
        $eventTypes = EventType::filter(Request::only('name', 'active'))->ordering(Request::only('fieldName','shortBy'))->orderBy('id','desc')->paginate(request()->perPage ?? $this->per_page)->withQueryString()
            ->through(fn ($groupType) => [
              'id' => $groupType->id,
              'name' => $groupType->name,
              'active' => $groupType->active,
            ]);
    
            $filters = Request::all('name', 'active');
    
            return Inertia::render('Admin/EventType/List', compact('filters','eventTypes'));
      } catch (\Exception $e) {
        Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
          return back()->with('error','Server error');
      }
    }

    public function eventTypeCreate() {
      if (request()->isMethod('post')) {
          // dd(request()->all());
          request()->validate([
              'name' => 'required|min:3',
              'active' => 'required',
          ],[
              'name.required' => 'The event type field is required.',
              'name.min' => 'The event type field must be at least 3 characters.',
              'active.required' => 'The status field is required.',

          ]);

          $eventType = new EventType();
          $eventType->name = request()->name;
          $eventType->active = request()->active;
          $eventType->save();
          return redirect()->route('admin.eventTypes')->with('success', 'Event type has been created successfully.');
      }

      return Inertia::render('Admin/EventType/CreateEdit');
  }

  public function eventTypeEdit(EventType $eventType) {
      if (request()->isMethod('post')) {
          request()->validate([
              'name' => 'required|min:3',
              'active' => 'required',
          ]);

          $eventType->name = request()->name;
          $eventType->active = request()->active;
          $eventType->save();
          return redirect()->route('admin.eventTypes')->with('success', 'Event type has been updated successfully.');
      }

      return Inertia::render('Admin/EventType/CreateEdit', compact('eventType'));
  }

  public function changeEventTypeStatus(EventType $eventType) {
      try {
          $eventType->active = $eventType->active == 1 ? 0 : 1;
          $eventType->save();
          session()->flash('success', 'Event type status has been updated successfully.');
          return back();
      } catch (\Exception $e) {
          Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
          return back()->with('error','Server error');
      }
  }

  public function deleteEventType(EventType $eventType) {
      try {
          // $eventType->group()->delete();
          $eventType->delete();
          return redirect()->route('admin.eventTypes')->with('success', 'Event Type has been deleted successfully.');
      } catch (\Exception $e) {
          Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
          return back()->with('error','Server error');
      }
  }
}
