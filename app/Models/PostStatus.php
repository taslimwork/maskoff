<?php

namespace App\Models;

use App\Traits\HashTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostStatus extends Model
{
    use HasFactory, SoftDeletes, HashTags;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'active' => 'string',
    ];
    protected $appends = [
        'number_of_comments'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function childComments()
    {
        return $this->hasMany(self::class, 'parent_id')->with('postDetails');
    }

    public function getNumberOfCommentsAttribute()
    {
        return $this->hasMany(self::class, 'parent_id')->count();
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function statusReaction()
    {
        return $this->hasMany(PostStatusReaction::class,'post_status_id', 'id');
    }


    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['name'] ?? null, function ($query, $search) {
            $query->whereHas('user', function($qry)use($search){
                $qry->whereRaw("CONCAT(first_name, isNull(last_name)) like '%" . trim($search) . "%'")
                    ->orWhere('username', "LIKE", "%" . trim($search) . "%");
            });
        });

        $query->when($filters['type'] ?? null, function ($query, $search) {
            $query->where('post_type','like', '%' . trim($search) . '%');
        });

        $query->when($filters['description'] ?? null, function ($query, $search) {
            $query->where('post_description','like', '%' . trim($search) . '%');
        });

        $query->when($filters['created_at'] ?? null, function ($query, $search) {
            $query->whereDate('created_at', $search);
        });


        $query->when(isset($filters['active']) ?? null, function ($query, $search) use($filters){
            $query->where('active',$filters['active']);
        });
    }

    public function scopeOrdering($query, array $filters)
    {
        // dd($filters);    
        $query->when($filters['fieldName'] ?? null, function ($query, $search) use($filters){
            $query->orderBy($search,$filters['shortBy']);
        });
    }

    public function postDetails()
    {
        return $this->HasMany(PostStatusDetail::class);
    }

    public function savedPost(){
        return $this->hasMany(SavedPosts::class, "post_id");
    }

    public function isSavedPost(){
        $isSaved = SavedPosts::where('post_id', $this->id)->where('user_id', auth()->id())->where('status', 1)->whereNull('deleted_at')->exists();
        if ($isSaved) {
            return true;
        }else {
            return false;
        }
    }

    public function isLikedPost(){
        $isLiked = PostStatusReaction::where('post_status_id', $this->id)->where('user_id', auth()->id())->exists();
        if ($isLiked) {
            return true;
        }else {
            return false;
        }
    }

    // public function hashTags(){
    //     return $this->morphMany(HashTag::class, 'model');
    // }
}
