<?php

namespace App\Traits;

use App\Models\HashTag;

trait HashTags{

    public function hashTags(){
        return $this->morphMany(HashTag::class, 'model');
    }

    public static function bootCommentable()
    {
        static::deleting(function ($model) {
            $model->comments()->delete();
        });
    }
}