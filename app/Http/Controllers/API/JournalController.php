<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Journal;
use App\Models\JournalDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


/**
 * @tags Journal-management
 */
class JournalController extends BaseController
{
     /**
     * @operationId journal-List
     *   @param string $title to filter the event.
     */
    public function journalList(Request $request)
    {
        try {
            $journal = Journal::filter($request->only('title'))
            ->orderBy('id','desc')
            ->paginate(10)->withQueryString()
                ->through(fn ($journal) => [
                'id' => $journal->id,
                'title' => $journal->title,
                'slug' => $journal->slug,
                'description' => $journal->description,
                'journal_details' => $journal->journalDetails,
                ]);

            return $this->sendResponse($journal, "All  list");
        } catch (\Throwable $th) {
            Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }

    /**
     * @operationId journal-Create
    */
    public function createJournal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:3|max:100',
            'description' => 'required',
            'images.*' => 'nullable|image|mimes:png,jpg,jpeg,gif|max:2048',
            'audio_file' => 'nullable|mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav',
        ]);

        if ($validator->fails()) {
            return parent::sendError($validator->errors()->first(), $validator->errors(), 422);
        }

        DB::beginTransaction();
        try {
            $user = auth()->user();
            $journal = new Journal();
            $journal->title = $request->title;
            $journal->user_id = $user->id;
            $journal->description = $request->description;
            $journal->save();
                // Handling multiple file uploads

            if ($request->hasFile('images')) {

                foreach ($request->file('images') as $image) {
                    $journalDetail = new JournalDetail();
                    $journalDetail->type = 'image';
                    $journalDetail->journal_id = $journal->id;
                    $journalDetail->file_path = $image->store('journal');
                    //dd($journalDetail);
                    $journalDetail->save();
                }
            }

            if ($request->hasFile('audio_file')) {
                $journalAudio = new JournalDetail();
                $journalAudio->type = 'audio';
                $journalAudio->type = $journal->id;
                $journalAudio->file_path =  $request->file('audio_file')->store('journal_audio');
                $journalAudio->save();
            }

            DB::commit();

            return $this->sendResponse($journal, 'Journal has been created successfully.', 200);
            // return $this->sendResponse($journal->with('journalDetail'), 'Event has been Updated successfully.');


        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }


    /**
     * @operationId journal-Edit
     *   @param Integer $id to find the journal.
    */
    public function editJournal(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:3|max:100',
            'description' => 'required',
            'images.*' => 'nullable|image|mimes:png,jpg,jpeg,gif|max:2048',
            'audio_file' => 'nullable|mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav',
        ]);

        if ($validator->fails()) {
            return parent::sendError($validator->errors()->first(), $validator->errors(), 422);
        }

        DB::beginTransaction();
        try {
            $user = auth()->user();
            $journal = Journal::find($id);
            $journal->title = $request->title;
            $journal->user_id = $user->id;
            $journal->description = $request->description;
            $journal->save();
                // Handling multiple file uploads

            if ($request->hasFile('images')) {

                foreach ($request->file('images') as $image) {
                    $journalDetail = new JournalDetail();
                    $journalDetail->type = 'image';
                    $journalDetail->journal_id = $journal->id;
                    $journalDetail->file_path = $image->store('journal');
                    //dd($journalDetail);
                    $journalDetail->save();
                }
            }

            if ($request->hasFile('audio_file')) {
                $journalAudio = new JournalDetail();
                $journalAudio->type = 'audio';
                $journalAudio->type = $journal->id;
                $journalAudio->file_path =  $request->file('audio_file')->store('journal_audio');
                $journalAudio->save();
            }

            DB::commit();

            return $this->sendResponse($journal, 'Event has been Updated successfully.');
            // return $this->sendResponse($journal->with('journalDetail'), 'Event has been Updated successfully.');


        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }

    }
}
