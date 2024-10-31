<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Traits\HasProfilePhoto;
use App\Traits\HashTags;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles,HasProfilePhoto, SoftDeletes, HashTags;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $appends = [
        'role_name', 'profile_photo_url'
    ];

    protected $fillable = [
        'name',
        'email',
        'username',
        'phone',
        'dob',
        'password',
        'google_id',
        'facebook_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'active' => 'string',
    ];

    // protected $append = ['role_name'];


    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }


    public function getRoleNameAttribute()
    {
        if ($this->roles()->exists())
            return $this->roles()->first()->name;
        else
            return 0;
    }
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getDobAttribute($val)
    {
        return $val ? date('m-d-Y', strtotime($val)) : null;
    }


    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['name'] ?? null, function ($query, $search) {
            $query->WhereRaw("concat(first_name,' ', last_name) like '%" . trim($search) . "%' ");
        });

        $query->when($filters['email'] ?? null, function ($query, $search) {
            $query->where('email','like', "%" . trim($search) . "%");
        });

        $query->when($filters['phone'] ?? null, function ($query, $search) {
            $query->where('phone','like', "%" . trim($search) . "%");
        });

        $query->when(isset($filters['active']) ?? null, function ($query, $search) use($filters){
            $query->where('active',$filters['active']);
        });

        $query->when($filters['contract_end_date'] ?? null, function ($query, $search) {
            $date = date('Y-m-d',strtotime($search));
            $query->whereDate('contract_end_date', '=', $date);
        });
    }

    public function scopeOrdering($query, array $filters)
    {
        $query->when($filters['fieldName'] ?? null, function ($query, $search) use($filters){
            $query->orderBy($search,$filters['shortBy']);
        });
    }

    public function group(){
        return $this->hasMany(Group::class, "created_by", "id");
    }


    public function invitations(){
        return $this->morphMany(Invitation::class, 'model');
    }

    public function connections(){
        return $this->hasMany(Invitation::class, "action_attempt_by", "id")->where('model_type', 'App\Models\User')->where('action', '1');
    }

    // public function hashTags(){
    //     return $this->morphMany(HashTag::class, 'model');
    // }
}
