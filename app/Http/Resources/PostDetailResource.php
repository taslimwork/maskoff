<?php

namespace App\Http\Resources;

use App\Models\PostStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PostDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $post = PostStatus::find($this->post_status_id);
        if ($post && ($post->post_type == 'photo' || $post->post_type == 'video' || $post->post_type == 'audio')) {
            $content = Storage::disk('public')->url($this->post_contents);
        }else{
            $content = $this->post_contents;
        }
        return [
            'id' => $this->id,
            'post_status_id' => $this->post_status_id,
            'post_contents' => $content,
            ];
    }
}
