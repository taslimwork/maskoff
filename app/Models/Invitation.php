<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invitation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'invitations';

    protected $fillable = ['type', 'sender', 'reciver', 'model_type', 'model_id', 'action', 'action_attempt_by'];

    public function invitation(){
        return $this->morphTo();
    }

    public function sendPerson(){
        return $this->belongsTo(User::class, 'sender', 'id');
    }

    public function recivePerson(){
        return $this->belongsTo(User::class, 'reciver', 'id');
    }

    public function actionAttemptBy(){
        return $this->belongsTo(User::class, 'action_attempt_by', 'id');
    }
}
