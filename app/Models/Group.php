<?php

namespace App\Models;

use App\Models\Traits\HasLogo;
use App\Traits\HashTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory, SoftDeletes, HasLogo, HashTags;

    protected $table = 'groups';

    protected $fillable = [
        'name', 'type_id', 'created_by', 'moto', 'group_logo','description', 'status'
    ];

    protected $appends = ['group_logo_url'];

    public function groupAdmin(){
        return $this->hasMany(GroupAdmin::class, 'group_id');
    }

    public function groupCreator(){
        return $this->belongsTo(User::class, "created_by", "id");
    }

    public function groupType(){
        return $this->belongsTo(GroupType::class, "type_id");
    }



    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['name'] ?? null, function ($query, $search) {
            $query->Where("name", "LIKE", "%" . trim($search) . "%");
        });

        $query->when(isset($filters['group_type']) ?? null, function ($query, $search) use($filters){
            $query->whereHas('groupType', function($qry)use($filters){
                $qry->where('type', "LIKE", "%" . trim($filters['group_type']) . "%" );
            });
        });

        $query->when($filters['moto'] ?? null, function ($query, $search) {
            $query->Where("moto", "LIKE", "%" . trim($search) . "%");
        });

        $query->when(isset($filters['creator']) ?? null, function ($query, $search) use($filters){
            $query->whereHas('groupCreator', function($qry)use($filters){
                $qry->whereRaw("CONCAT(first_name, ' ',isNull(last_name)) like '%" . trim($filters['creator']) . "%'" );
            });
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

    public function invitations(){
        return $this->morphMany(Invitation::class, 'model');
    }

    public function members(){
        return $this->hasMany(GroupMembers::class, 'group_id', "id");
    }

    public function follower(){
        return $this->hasMany(GroupFollower::class, "group_id");
    }
}
