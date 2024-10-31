<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventAttendingStatus;
use App\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


/**
 * @tags Event-Management
 */
class EventManagementController extends BaseController
{

    /**
     * @operationId All-Event-Types
     * @unauthenticated
    */
    public function getEventTypes()
    {
        try {

            $eventTypes = EventType::all();
            return $this->sendResponse($eventTypes, "Event type list");

        } catch (\Throwable $th) {
            Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }

    /**
     * @operationId All-Event-List
     * @unauthenticated
     *  @param string $name to filter the event.
    */
    public function allEventList(Request $request)
    {
        try {
            $events = Event::filter($request->only('name'))
            ->orderBy('event_date','desc')
            ->paginate(10)->withQueryString()
                ->through(fn ($event) => [
                'id' => $event->id,
                'name' => $event->name,
                'event_type' => $event->event_type?->name,
                'organizer_name' => $event->organizer_name,
                'event_date' => $event->event_date,
                'event_time' => $event->event_time,
                'event_location' => $event->event_location,
                'attending_users' => $event->attendingUsers,
                'number_of_attending_users' => $event->attendingUsers?->count(),
                'image' => $event->image
                ]);

            return $this->sendResponse($events, "All Event list");
        } catch (\Throwable $th) {
            Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }
    /**
     * @operationId My-Event-List
    */
   public function myEventList(Request $request)
    {
        try {
            $user = auth()->user();
            $events = Event::filter($request->only('name'))
            ->where('user_id', $user->id)
            ->orderBy('event_date','desc')
            ->paginate(10)->withQueryString()
                ->through(fn ($event) => [
                'id' => $event->id,
                'name' => $event->name,
                'event_type' => $event->event_type?->name,
                'organizer_name' => $event->organizer_name,
                'event_date' => $event->event_date,
                'event_time' => $event->event_time,
                'event_location' => $event->event_location,
                'attending_users' => $event->attendingUsers->makeHidden(['id', 'user_id', 'event_id', 'created_at', 'updated_at']),
                'number_of_attending_users' => $event->attendingUsers?->count(),
                'image' => $event->event_logo_url
                ]);

            return $this->sendResponse($events, "All Event list");
        } catch (\Throwable $th) {
            Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }

    /**
     * @operationId Event-Details
     * @unauthenticated
    */
    public function eventDetails($id)
    {
        try {
            $event = Event::find($id)->makeHidden(['slug', 'event_type_id', 'created_at', 'updated_at']);
            $attendingUsers = $event->attendingUsers;
            $user = [];
            
            foreach ($attendingUsers as $attendingUser) {
                $user[] = $attendingUser->makeHidden(['id', 'user_id', 'event_id', 'created_at', 'updated_at']);
            }

            $name = $event->event_type->name;
            $event['event_type_name'] = $name;
            
            $event['attending_users'] = $user;

            $event->makeHidden(['event_type']);
            return $this->sendResponse($event, "Event Details retrive", 200);
        } catch (\Exception $th) {
            Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }

    /**
     * @operationId Create-Event
    */
    public function createEvent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'event_type_id' => 'required|exists:event_types,id',
            'organizer_name' => 'nullable|string|max:255',
            'event_date' => 'required|date|date_format:Y-m-d|after:yesterday',
            'event_time' => 'required',
            'event_location' => 'required|string|max:2024',
            'description' => 'required|string',
            'event_features' => 'nullable|string',
            'sponsor_information' => 'nullable|Array',
            "image" => 'nullable|mimes:jpg,jpeg,png',
            'hashtags' => 'required|array'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->errors(), 422);
        }

        DB::beginTransaction();
        try {
            $user = auth()->user();
            $event = new Event();

            if( $request->file('image')){
                $event->image =  $request->file('event')->store('event');
            }

            $event->user_id = $user->id;
            $event->name =  $request->name;
            $event->event_type_id =  $request->event_type_id;
            $event->organizer_name =  $request->organizer_name;
            $event->event_date =  $request->event_date;
            $event->event_time =  $request->event_time;
            $event->event_location =  $request->event_location;
            $event->description =  $request->description;
            $event->event_features =  $request->event_features;
            $event->sponsor_information = ( $request->sponsor_information != '') ? json_encode( $request->sponsor_information): json_encode([]);
            $event->save();

            $event->attendingStatus()->create([
                'user_id' => auth()->id(),
                'status' => 'Attending',
            ]);

            foreach ($request->hashtags as $tag) {
                $event->hashTags()->create([
                    'tag_title' => $tag
                ]);
            }

            $event->hash_tags = $event->hashTags;

            DB::commit();

            return $this->sendResponse($event, 'Event has been created successfully.');

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }

    /**
     * @operationId Edit-Event
    */
    public function editEvent(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'event_type_id' => 'required|exists:event_types,id',
            'organizer_name' => 'nullable|string|max:255',
            'event_date' => 'required|date|date_format:Y-m-d|after:yesterday',
            'event_time' => 'required',
            'event_location' => 'required|string|max:2024',
            'description' => 'required|string',
            'event_features' => 'nullable|string',
            'sponsor_information' => 'nullable|Array',
            "image" => 'nullable|mimes:jpg,jpeg,png',
            "hash_tags" => 'required|array'
        ]);


        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->errors(), 422);
        }

        DB::beginTransaction();
        try {
            $user = auth()->user();
            $event = Event::find($id);

            if($request->file('image')){
                $event->image = $request->file('event')->store('event');
            }

            $event->user_id = $user->id;
            $event->name = $request->name;
            $event->event_type_id = $request->event_type_id;
            $event->organizer_name = $request->organizer_name;
            $event->event_date = $request->event_date;
            $event->event_time = $request->event_time;
            $event->event_location = $request->event_location;
            $event->description = $request->description;
            $event->event_features = $request->event_features;
            $event->sponsor_information = ($request->sponsor_information != '') ? json_encode($request->sponsor_information): json_encode([]);
            $event->save();

            if ($request->hash_tags) {
                $event->hashTags()->delete();
                foreach ($request->hash_tags as $tag) {
                    $event->hashTags()->create([
                        'tag_title' => $tag
                    ]);
                }
            }

            $event->hash_tags = $event->hashTags;

            DB::commit();

            return $this->sendResponse($event, 'Event has been Updated successfully.');

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }
      /**
     * @operationId Update-status-event-attending
    */
     public function addAttendingStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id',
            'status' => 'required|in:Attending,Not-Attending,May-Be-Attending',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->errors(), 422);
        }

        DB::beginTransaction();

        try{
            $user = auth()->user();
            $existStatus = EventAttendingStatus::where('user_id', $user->id)->where('event_id', $request->event_id)->first();

            if(!$existStatus)
            {
                $existStatus = new EventAttendingStatus();
                $existStatus->user_id = $user->id;
            }

            $existStatus->event_id = $request->id;
            $existStatus->status = $request->status;
            $existStatus->save();
            DB::commit();

            return $this->sendResponse($existStatus, 'Event status has been updated successfully.');

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }
    /**
     * @operationId Send Invitation on an event
    */
    public function sendEventInvitation(Request $request) {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required',
            'invitee' => 'required|array'
        ]);

        if ($validator->fails()) {
            return parent::sendError($validator->errors()->first(), $validator->errors(), 422);
        }

        try {
            $event = Event::find($request->event_id);
            foreach ($request->invitee as $invitee) {
                $invite = $event->invitations()->create([
                    'sender' => auth()->id(),
                    'reciver' => $invitee
                ]);

                $response[] = $invite->recivePerson->only('full_name', 'username', 'profile_photo_url');
            }
            return parent::sendResponse($response, "Invitation sent", 422);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }
    /**
     * @operationId Event Attending Status
    */
    public function eventAttendingStatus() {
        try {
            $actions = [
                'Attending', 'Not-Attending', 'May-Be-Attending'
            ];

            return parent::sendResponse($actions, "Event Attending Status retrived", 200);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }
    /**
     * @operationId Event Attend
    */
    public function eventAttend(Request $request){
        $validator = Validator::make($request->all(), [
            'event_id' => 'required',
            'status' => 'required|string'
        ]);
        
        if ($validator->fails()) {
            return parent::sendError($validator->errors()->first(), $validator->errors(), 422);
        }

        try {
            $event = Event::find($request->event_id);
            $event->invitations()->update([
                'action' => 1
            ]);

            $attend = $event->attendingStatus()->create([
                'user_id' => auth()->id(),
                'event_id' => $request->event_id,
                'status' => $request->status
            ]);

            $data = [
                'user' => $attend->user->only('full_name', 'username', 'profile_photo_url'),
                'event' => $attend->event->only('name', 'event_date', 'event_time', 'event_location', 'description', 'sponsor_information', 'event_logo_url', 'event_features'),
                'status' => $attend->status
            ];

            return parent::sendResponse($data, "You've atteneded event", 200);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }
}
