<?php

namespace App\Services;

use App\Http\Resources\PostResource;
use App\Models\PostStatus;
use App\Models\SavedPosts;

class PostService{
    
    public function myPost($authId){
        $allPosts = PostStatus::with(['postDetails','childComments','statusReaction'])->where(function ($query) use($authId){
            $query->where('user_id', $authId);
            // ->where('active', 1)
            //       ->where('isSpam', 0);
        })
        ->orderBy('id', 'desc')
        ->paginate(10, ['*'], 'post_page')->withQueryString();

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
        return $response;
    }

    public function getInterestsPosts($pageParam, $userId){
        $allPosts = PostStatus::with(['postDetails','childComments','statusReaction'])
                    ->where('active', 1)
                    ->where('isSpam', 0)
                    ->whereHas('savedPost' ,function($query)use($userId){
                       $query->where('user_id', $userId);
                    })
        ->orderBy('id', 'desc')
        ->paginate(10, ['*'], $pageParam)->withQueryString();
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
        return $response;
    }

    public function getContent(){
        return [];
    }
}
