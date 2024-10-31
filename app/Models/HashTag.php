<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HashTag extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hash_tags';

    protected $hidden = ['id', 'model_type', 'model_id', 'created_at', 'updated_at', 'deleted_at'];

    protected $fillable = [
        'tag_title', 'model_type', 'model_id'
    ];

    public function model(){
        return $this->morphTo();
    }
}
