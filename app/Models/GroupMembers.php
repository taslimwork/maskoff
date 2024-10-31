<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupMembers extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'group_members';

    protected $fillable = ['group_id', 'user_id', 'is_admin', 'status'];

    public function group(){
        return $this->belongsTo(Group::class, "group_id");
    }

    public function user(){
        return $this->belongsTo(User::class, "user_id");
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['name'] ?? null, function ($query, $search) {
            $query->whereHas("user", function($qry) use($search){
                $qry->WhereRaw("concat(first_name,' ', last_name) like '%" . trim($search) . "%' ");
            });
        });

        $query->when($filters['email'] ?? null, function ($query, $search) {
            $query->whereHas("user", function($qry) use($search){
                $qry->where('email','like', "%" . trim($search) . "%");
            });
        });

        $query->when($filters['username'] ?? null, function ($query, $search) {
            $query->whereHas("user", function($qry) use($search){
                $qry->where('username','like', "%" . trim($search) . "%");
            });
        });

        $query->when($filters['role'] ?? null, function ($query, $search) use($filters) {
            $query->where('is_admin', $filters['role']);
        });

        $query->when(isset($filters['status']) ?? null, function ($query, $search) use($filters){
            $query->where('status',$filters['status']);
        });
    }

    public function scopeOrdering($query, array $filters)
    {
        $query->when($filters['fieldName'] ?? null, function ($query, $search) use($filters){
            $query->orderBy($search,$filters['shortBy']);
        });
    }
}
