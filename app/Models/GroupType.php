<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'group_types';

    protected $fillable = [
        'type', 'slug', 'status'
    ];

    public function group(){
        return $this->hasMany(Group::class, "type_id");
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['type'] ?? null, function ($query, $search) {
            $query->where('type','like', "%" . trim($search) . "%");
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
