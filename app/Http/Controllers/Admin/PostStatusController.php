<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostDetailResource;
use App\Http\Resources\PostResource;
use App\Models\PostStatus;
use App\Models\PostStatusReaction;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PostStatusController extends Controller
{
   public function postList()
   {
        try
        {
            $filters = request()->all('name', 'type', 'description', 'total_comment', 'total_reaction','created_at','active');
            $postList = PostStatus::filter(request()->only('name', 'type', 'description', 'total_comment', 'total_reaction','created_at','active'))
                    ->where('parent_id',0)
                    ->with(['postDetails','childComments','statusReaction'])
                    ->ordering(request()->only('fieldName','shortBy'))
                    ->orderBy('id','desc')
                    ->paginate(request()->perPage ?? $this->per_page)->withQueryString()
                    ->through(function($post){
                        return [
                          'id' => $post->id,
                          'post_type' => $post->post_type,
                          'title' => $post->title,
                          'post_description' => $post->post_description,
                          'user' => [ 
                              'user_id'=> $post->user->id,
                              'full_name'=>$post->user->full_name,
                              'username'=>$post->user->username,
                              'image'=>$post->user->profile_photo_url
                          ],
                          'post_details' => PostDetailResource::collection($post->postDetails),
                          'list_of_comments' =>  PostResource::collection($post->childComments),
                          'number_of_comments' => $post->number_of_comments,
                          'number_of_reaction' => $post->statusReaction?->count() ,
                          // 'reaction_list' => $post->statusReaction ,
                          'created_at' => $post->created_at,
                          'active' => $post->active,
                        ];
                    });


                  // $postList = PostResource::collection( $postData);

            return Inertia::render('Admin/Post-status/PostList',compact('postList','filters'));

        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server Error');
        }
   }
   public function changeBlockUnblockStatus($id)
   {
     try{
        $postStatus =  PostStatus::find($id);
         $postStatus->active = ($postStatus->active == 1) ? 0 : 1 ;
         $postStatus->save();
         session()->flash('success', 'Post status has been changed successfully.');
         return back();
       } catch (\Exception $e) {
         Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
         return back()->with('error','Server error');
     }
   }

   public function postDestroy($id)
   {
     try{
        $comments = PostStatus::where('parent_id', $id)->delete();
        $reaction = PostStatusReaction::where('post_status_id', $id)->delete();
        $report = Report::where('post_id', $id)->delete();
        $postStatus =  PostStatus::find($id);
         $postStatus->delete();
         session()->flash('success', 'Post has been deleted successfully.');
         return back();
       } catch (\Exception $e) {
         Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
         return back()->with('error','Server error');
     }
   }
   public function postComment(Request $request)
   {
        $request->validate([
            'post_comment' => 'required',
            'post_status_id' => 'required',
        ]);

        try{
            $strategySub = new PostStatus();
            $strategySub->post_type = 'text';
            $strategySub->post_description = $request->post_comment;
            $strategySub->parent_id = $request->post_status_id;
            $strategySub->user_id = Auth::id();
            $strategySub->save();
            session()->flash('success', 'Comment has been added successfully.');
            return back();
            // return redirect()->route('admin.post-status');

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server error');
        }
   }

   public function postCommentDelete($id)
   {
     try{
        $postStatus =  PostStatus::find($id);
         $postStatus->delete();
         session()->flash('success', 'Comment has been deleted successfully.');
         return back();
       } catch (\Exception $e) {
         Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
         return back()->with('error','Server error');
     }
   }
}
