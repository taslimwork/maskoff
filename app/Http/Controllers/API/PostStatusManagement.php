<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\PostStatus;
use App\Models\PostStatusDetail;
use App\Models\PostStatusReaction;
use App\Models\SavedPosts;
use App\Models\User;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * @tags Post-Status-Management
 */
class PostStatusManagement extends BaseController
{

    public $_postService;

    public function __construct(PostService $postService){
        $this->_postService = $postService;
    }

    /**
     * @operationId Post-list
     */
    public function postList()
    {
        try {
            $user = auth()->user();

            $friendsIds = ['3','7'];

            $allPosts = PostStatus::with(['postDetails','childComments','statusReaction'])->where(function ($query) {
                    $query->where('active', 1)
                          ->where('isSpam', 0)->where('parent_id', 0);
                })
                ->where(function ($query) use ($friendsIds) {
                    $query->where('post_audience', 'friends')
                            ->whereIn('user_id', $friendsIds);
                    $query->orWhere(function ($query1){
                        $query1->where('post_audience', 'public');
                    });
                })
                ->orWhere('user_id', $user->id)
                ->orderBy('id', 'desc')
                ->paginate($this->per_page);

            $data = PostResource::collection($allPosts);
            $response = [            
                'current_page' => $allPosts->currentPage(),
                'posts' => $data,
                'first_page_url' => $allPosts->url(1),
                'from' => $allPosts->firstItem(),
                'last_page' => $allPosts->lastPage(),
                'last_page_url' => $allPosts->url($allPosts->lastPage()),
                'next_page_url' => $allPosts->nextPageUrl(),
                'path' => $allPosts->path(),
                'per_page' => $allPosts->perPage(),
                'prev_page_url' => $allPosts->previousPageUrl(),
                'to' => $allPosts->lastItem(),
                'total' => $allPosts->total(),
            ];

            return $this->sendResponse($response, "All post list");
        } catch (\Throwable $th) {
            Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }

    public function myPost()
    {
        try {
            // $allPosts = PostStatus::with(['postDetails','childComments','statusReaction'])->where(function ($query) {
            //         $query->where('user_id', auth()->user()->id);
            //         // ->where('active', 1)
            //         //       ->where('isSpam', 0);
            //     })
            //     ->orderBy('id', 'desc')
            //     ->paginate(10)->withQueryString();

            // $data = PostResource::collection($allPosts);
            $authId = auth()->user()->id;
            $data = $this->_postService->myPost($authId);

            return $this->sendResponse($data, "My post list");
        } catch (\Throwable $th) {
            Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }

    public function addReaction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_status_id' => 'required|exists:post_statuses,id'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->errors(), 422);
        }

        try {
            $user = auth()->user();
            $reactionExists = PostStatusReaction::where('user_id',$user->id)->where('post_status_id', $request->post_status_id)->first();
            if( $reactionExists)
            {
                $reactionExists->delete();
                return $this->sendResponse([], "You have successfully unliked the post.");
            }
            else{

                $PostStatusReaction = new PostStatusReaction();

                $PostStatusReaction->user_id =$user->id;
                $PostStatusReaction->post_status_id =$request->post_status_id;
                $PostStatusReaction->save();

                return $this->sendResponse($PostStatusReaction, "You have successfully unliked the post.");
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }


    public function createPost_old(Request $request)
    {
        Log::debug("Post Create:: ".print_r($request->all(), true));
        $validator = Validator::make($request->all(), [
            'post_type' => 'required|in:text,photo,video,audio,poll,article',
            'post_description' => 'required',
            'photo' => 'required_if:post_type,photo|image|mimes:png,jpg,jpeg,gif|max:2048',
            'video_file' => 'required_if:post_type,video|file|mimes:mp4,mov,ogg,qt|max:2000000',
            'audio_file' => 'required_if:post_type,audio|file|mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav',
            'hash_tags' => 'nullable|array',
            'post_audience' => 'required|in:public,friends,only_me'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->errors(), 422);
        }

        $user = auth()->user();
        $post = new PostStatus();

        $post->post_type = $request->post_type;
        $post->post_description = $request->post_description;
        $post->user_id = $user->id;
        $post->post_audience = $request->post_audience;
        $post->save();

        if ($request->hash_tags) {
            foreach ($request->hash_tags as $tag) {
                $post->hashTags()->create([
                    'tag_title' => $tag
                ]);
            }
        }

        $data = new PostResource($post);


        if($request->post_type == 'text')
        {
            // $validator = Validator::make($request->all(), [
            //     'post_type' => 'required|in:text,photo,video,audio,poll,article',
            //     'post_description' => 'required',
            // ]);
            // if ($validator->fails()) {
            //     return $this->sendError($validator->errors()->first(), $validator->errors(), 422);
            // }
            DB::beginTransaction();
            try {
                // $user = auth()->user();
                // $post = new PostStatus();

                // $post->post_type = $request->post_type;
                // $post->post_description = $request->post_description;
                // $post->user_id = $user->id;
                // $post->post_audience = $request->post_audience;
                // $post->save();

                // if ($request->hash_tags) {
                //     foreach ($request->hash_tags as $tag) {
                //         $post->hashTags()->create([
                //             'tag_title' => $tag
                //         ]);
                //     }
                // }

                DB::commit();
                $data = new PostResource($post);
                return $this->sendResponse($data, "Post has been created successfully.");
            } catch (\Throwable $th) {
                DB::rollBack();
                Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
                return $this->sendError('Server Error!', [], 500);
            }
        }

        if($request->post_type == 'photo')
        {
            // $validator = Validator::make($request->all(), [
            //     'post_type' => 'required|in:text,photo,video,audio,poll,article',
            //     'post_description' => 'required',
            //     'photo' => 'required|image|mimes:png,jpg,jpeg,gif|max:2048',
            // ]);
            // if ($validator->fails()) {
            //     return $this->sendError($validator->errors()->first(), $validator->errors(), 422);
            // }

            DB::beginTransaction();
            try {
                // $user = auth()->user();
                // $post = new PostStatus();
                // $post->post_type = $request->post_type;
                // $post->post_description = $request->post_description;
                // $post->user_id = $user->id;
                // $post->post_audience = $request->post_audience;
                // $post->save();

                if ($request->hasFile('photo')) {
                    $postDetail = new PostStatusDetail();
                    $postDetail->post_status_id = $post->id;
                    $postDetail->post_contents = $request->photo->store('post_image');
                    $postDetail->save();
                }

                if ($request->hash_tags) {
                    foreach ($request->hash_tags as $tag) {
                        $post->hashTags()->create([
                            'tag_title' => $tag
                        ]);
                    }
                }

                DB::commit();
                $data = new PostResource($post);

                return $this->sendResponse($data, "Post has been created successfully.");
            } catch (\Throwable $th) {
                DB::rollBack();
                Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
                return $this->sendError('Server Error!', [], 500);
            }
        }

        if($request->post_type == 'audio')
        {
            // $validator = Validator::make($request->all(), [
            //     'post_type' => 'required|in:text,photo,video,audio,poll,article',
            //     'post_description' => 'required',
            //     'audio_file' => 'required|mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav',
            // ]);
            // if ($validator->fails()) {
            //     return $this->sendError($validator->errors()->first(), $validator->errors(), 422);
            // }

            DB::beginTransaction();
            try {
                $user = auth()->user();
                $post = new PostStatus();
                $post->post_type = $request->post_type;
                $post->post_description = $request->post_description;
                $post->user_id = $user->id;
                $post->post_audience = $request->post_audience;
                $post->save();

                if ($request->hasFile('audio_file')) {

                    $postDetail = new PostStatusDetail();
                    $postDetail->post_status_id = $post->id;
                    $postDetail->post_contents = $request->file('audio_file')->store('post_audio');
                    $postDetail->save();
                }

                if ($request->hash_tags) {
                    foreach ($request->hash_tags as $tag) {
                        $post->hashTags()->create([
                            'tag_title' => $tag
                        ]);
                    }
                }

                DB::commit();
                $data = new PostResource($post);

                return $this->sendResponse($data, "Post has been created successfully.");
            } catch (\Throwable $th) {
                DB::rollBack();
                Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
                return $this->sendError('Server Error!', [], 500);
            }
        }
        if($request->post_type == 'video')
        {
            // $validator = Validator::make($request->all(), [
            //     'post_type' => 'required|in:text,photo,video,audio,poll,article',
            //     'post_description' => 'required',
            //     'video_file' => 'required|mimes:mp4,mov,ogg,qt|max:2000000',
            // ]);
            // if ($validator->fails()) {
            //     return $this->sendError($validator->errors()->first(), $validator->errors(), 422);
            // }

            DB::beginTransaction();
            try {
                $user = auth()->user();
                $post = new PostStatus();
                $post->post_type = $request->post_type;
                $post->post_description = $request->post_description;
                $post->user_id = $user->id;
                $post->post_audience = $request->post_audience;
                $post->save();

                if ($request->hasFile('video_file')) {

                    $postDetail = new PostStatusDetail();
                    $postDetail->post_status_id = $post->id;
                    $postDetail->post_contents = $request->file('video_file')->store('post_video');
                    $postDetail->save();
                }

                if ($request->hash_tags) {
                    foreach ($request->hash_tags as $tag) {
                        $post->hashTags()->create([
                            'tag_title' => $tag
                        ]);
                    }
                }

                DB::commit();
                $data = new PostResource($post);

                return $this->sendResponse($data, "Post has been created successfully.");
            } catch (\Throwable $th) {
                DB::rollBack();
                Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
                return $this->sendError('Server Error!', [], 500);
            }
        }
        if($request->post_type == 'poll')
        {
            $validator = Validator::make($request->all(), [
                // 'post_type' => 'required|in:text,photo,video,audio,poll,article',
                // 'post_description' => 'required',
                'poll_data.*' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first(), $validator->errors(), 422);
            }

            DB::beginTransaction();
            try {
                $user = auth()->user();
                $post = new PostStatus();
                $post->post_type = $request->post_type;
                $post->post_description = $request->post_description;
                $post->user_id = $user->id;
                $post->post_audience = $request->post_audience;
                $post->save();

                foreach ($request->poll_data as $key => $value){
                    $postDetail = new PostStatusDetail();
                    $postDetail->post_status_id = $post->id;
                    $postDetail->post_contents = $value;
                    $postDetail->save();
                }

                if ($request->hash_tags) {
                    foreach ($request->hash_tags as $tag) {
                        $post->hashTags()->create([
                            'tag_title' => $tag
                        ]);
                    }
                }

                DB::commit();
                $data = new PostResource($post);

                return $this->sendResponse($data, "Post has been created successfully.");
            } catch (\Throwable $th) {
                DB::rollBack();
                Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
                return $this->sendError('Server Error!', [], 500);
            }
        }

        if($request->post_type == 'article')
        {
            $validator = Validator::make($request->all(), [
                // 'post_type' => 'required|in:text,photo,video,audio,poll,article',
                // 'post_description' => 'required',
                'article_link' => 'required|url',
            ]);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first(), $validator->errors(), 422);
            }

            DB::beginTransaction();
            try {
                $user = auth()->user();
                $post = new PostStatus();
                $post->post_type = $request->post_type;
                $post->post_description = $request->post_description;
                $post->user_id = $user->id;
                $post->post_audience = $request->post_audience;
                $post->save();

                $postDetail = new PostStatusDetail();
                $postDetail->post_status_id = $post->id;
                $postDetail->post_contents = $request->article_link;
                $postDetail->save();

                if ($request->hash_tags) {
                    foreach ($request->hash_tags as $tag) {
                        $post->hashTags()->create([
                            'tag_title' => $tag
                        ]);
                    }
                }
                
                DB::commit();
                $data = new PostResource($post);

                return $this->sendResponse($data, "Post has been created successfully.", 200);
            } catch (\Throwable $th) {
                DB::rollBack();
                Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
                return $this->sendError('Server Error!', [], 500);
            }
        }
    }
    /**
     * @operationId Post-Create
     */
    public function createPost(Request $request){
        $validator = Validator::make($request->all(), [
            'post_type' => 'required|in:text,photo,video,audio,poll,article',
            'post_description' => 'required',
            'photo' => 'required_if:post_type,photo|image|mimes:png,jpg,jpeg,gif|max:2048',
            'video_file' => 'required_if:post_type,video|file|mimes:mp4,mov,ogg,qt|max:2000000',
            'audio_file' => 'required_if:post_type,audio|file|mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav',
            'hash_tags' => 'nullable|array',
            'poll_data' => 'required_if:post_type,poll|array|min:2',
            'article_link' => 'required_if:post_type,article|url',
            'post_audience' => 'required|in:public,friends,only_me'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->errors(), 422);
        }

        try {
            DB::beginTransaction();

            $user = auth()->user();
            $post = new PostStatus();
    
            $post->post_type = $request->post_type;
            $post->post_description = $request->post_description;
            $post->user_id = $user->id;
            $post->post_audience = $request->post_audience;
            $post->save();
    
            if ($request->hash_tags) {
                foreach ($request->hash_tags as $tag) {
                    $post->hashTags()->create([
                        'tag_title' => $tag
                    ]);
                }
            }

            if($request->post_type == 'photo'){
                if ($request->hasFile('photo')) {
                    $postDetail = new PostStatusDetail();
                    $postDetail->post_status_id = $post->id;
                    $postDetail->post_contents = $request->photo->store('post_image');
                    $postDetail->save();
                }
            }

            if($request->post_type == 'video'){
                if ($request->hasFile('video_file')) {
                    $postDetail = new PostStatusDetail();
                    $postDetail->post_status_id = $post->id;
                    $postDetail->post_contents = $request->file('video_file')->store('post_video');
                    $postDetail->save();
                }
            }

            if($request->post_type == 'audio'){
                if ($request->hasFile('audio_file')) {
                    $postDetail = new PostStatusDetail();
                    $postDetail->post_status_id = $post->id;
                    $postDetail->post_contents = $request->file('audio_file')->store('post_audio');
                    $postDetail->save();
                }
            }

            if($request->post_type == 'poll'){
                foreach ($request->poll_data as $key => $value){
                    $postDetail = new PostStatusDetail();
                    $postDetail->post_status_id = $post->id;
                    $postDetail->post_contents = $value;
                    $postDetail->save();
                }
            }

            if($request->post_type == 'article'){
                $postDetail = new PostStatusDetail();
                $postDetail->post_status_id = $post->id;
                $postDetail->post_contents = $request->article_link;
                $postDetail->save();
            }
    
            $data = new PostResource($post);
            return $this->sendResponse($data, "Post has been created successfully.", 200);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }

    }

    public function updatePost(Request $request){
        $validator = Validator::make($request->all(), [
            'post_id' => 'required',
            'post_type' => 'required|in:text,photo,video,audio,poll,article',
            'post_description' => 'required',
            'photo' => 'required_if:post_type,photo|image|mimes:png,jpg,jpeg,gif|max:2048',
            'video_file' => 'required_if:post_type,video|file|mimes:mp4,mov,ogg,qt|max:2000000',
            'audio_file' => 'required_if:post_type,audio|file|mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav',
            'hash_tags' => 'required|array',
            'poll_data' => 'required_if:post_type,poll|array',
            'article_link' => 'required_if:post_type,article|url',
            'post_audience' => 'required|in:public,friends,only_me'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->errors(), 422);
        }
        
        try {
            $post = PostStatus::where('parent_id', 0)->find($request->post_id);
            $user = auth()->user();

            $post->post_type = $request->post_type;
            $post->post_description = $request->post_description;
            $post->user_id = $user->id;
            $post->post_audience = $request->post_audience;
            $post->save();

            if ($request->post_type == "photo") {

                if ($request->hasFile('photo')) {
                    $content = $post->postDetails;

                    foreach ($content as $key => $value) {
                        if (file_exists($value->post_contents)) {
                            unlink($value->post_contents);
                        }
                        $value->delete();
                    }

                    $postDetail = new PostStatusDetail();
                    $postDetail->post_status_id = $post->id;
                    $postDetail->post_contents = $request->photo->store('post_image');
                    $postDetail->save();
                }
            }

            if ($request->post_type == "audio") {
                if ($request->hasFile('audio_file')) {
                    $content = $post->postDetails;

                    foreach ($content as $key => $value) {
                        if (file_exists($value->post_contents)) {
                            unlink($value->post_contents);
                        }
                        $value->delete();
                    }
                    $postDetail = new PostStatusDetail();
                    $postDetail->post_status_id = $post->id;
                    $postDetail->post_contents = $request->file('audio_file')->store('post_audio');
                    $postDetail->save();
                }
            }

            if ($request->post_type == "video") {
                if ($request->hasFile('video_file')) {
                    $content = $post->postDetails;
                    foreach ($content as $key => $value) {
                        if (file_exists($value->post_contents)) {
                            unlink($value->post_contents);
                        }
                        $value->delete();
                    }
                    
                    $postDetail = new PostStatusDetail();
                    $postDetail->post_status_id = $post->id;
                    $postDetail->post_contents = $request->file('video_file')->store('post_video');
                    $postDetail->save();
                }
            }

            if($request->post_type == 'poll'){

                foreach ($post->postDetails as $value) {
                    $value->delete();
                }
                
                foreach ($request->poll_data as $value){
                    $postDetail = new PostStatusDetail();
                    $postDetail->post_status_id = $post->id;
                    $postDetail->post_contents = $value;
                    $postDetail->save();
                }

            }

            if($request->post_type == 'article'){
                foreach ($post->postDetails as $value) {
                    $value->delete();
                }

                $postDetail = new PostStatusDetail();
                $postDetail->post_status_id = $post->id;
                $postDetail->post_contents = $request->article_link;
                $postDetail->save();
            }

            $post = PostStatus::find($request->post_id);
            $data = new PostResource($post);
            return parent::sendResponse($data, "Post Updated", 200);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }
    /**
     * @operationId Post-Save-Unsave
     */
    public function savePost(Request $request){
        $validator = Validator::make($request->all(), [
            'post_id' => 'required'
        ]);

        if ($validator->fails()) {
            return parent::sendError($validator->errors()->first(), $validator->errors(), 422);
        }

        try {
            $post = PostStatus::find($request->post_id)->where('parent_id', 0);
            if (!$post) {
                return parent::sendError("Invalid Post", [], 422);
            }

            $isSaved = SavedPosts::where('user_id', Auth::id())->where('post_id', $request->post_id)->first();
            if ($isSaved) {
                $isSaved->delete();
                return parent::sendResponse([], "Unsaved post", 200);
            }else {
                SavedPosts::create([
                    'post_id' => $request->post_id,
                    'user_id' => auth()->id(),
                ]);
                return parent::sendResponse([], 'Saved post', 200);
            }
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }
    /**
     * @operationId Interest Posts
     */
    public function interestPosts(){
        try {
            $userId = auth()->user()->id;
            $data = $this->_postService->getInterestsPosts('page', $userId);
            return parent::sendResponse($data, "Interest posts retrived", 200);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }
}
