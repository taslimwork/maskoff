<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupFollower extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'group_followers';

    protected $fillable = [
        'group_id', 'user_id', 'status'
    ];

    public function user(){
        return $this->belongsTo(User::class, "user_id");
    }

    public function group(){
        return $this->belongsTo(Group::class, "group_id");
    }
}
