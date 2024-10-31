<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = [ 'user_id'=> $this->user->id,
                  'full_name'=>$this->user->full_name,
                  'username'=>$this->user->username,
                  'image'=>$this->user->profile_photo_url
                ];

        return [
            'id' => $this->id,
            'post_type' => $this->post_type,
            'title' => $this->title,
            'post_description' => $this->post_description,
            'user' => $user,
            'post_details' => PostDetailResource::collection($this->postDetails),
            'list_of_comments' =>  PostResource::collection($this->childComments),
            'number_of_comments' => $this->number_of_comments,
            'number_of_reaction' => $this->statusReaction?->count() ,
            // 'reaction_list' => $this->statusReaction ,
            'is_saved' => $this->isSavedPost(),
            'is_like' => $this->isLikedPost(),
            'created_at' => $this->created_at,
            'active' => $this->active,
            'hashTags' => $this->hashTags
        ];
    }
}
