<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "reports";

    protected $fillable = [
        'report_type_id', 'user_id', 'post_id', 'status'
    ];

    public function reportType(){
        return $this->belongsTo(ReportType::class, "report_type_id");
    }

    public function user(){
        return $this->belongsTo(User::class, "user_id");
    }

    public function post(){
        return $this->belongsTo(PostStatus::class, "post_id");
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['name'] ?? null, function ($query, $search) {
            $query->whereHas('user', function($qry) use($search){
                $qry->whereRaw("CONCAT(first_name, isNull(last_name)) like '%" . trim($search) . "%'")
                    ->orWhere('username', "LIKE", "%" . trim($search) . "%");
            });
        });

        $query->when($filters['report_type'] ?? null, function ($query, $search) {
            $query->whereHas('reportType', function($qry) use($search){
                $qry->where('name', "LIKE", "%" . trim($search) . "%");
            });
        });

        $query->when($filters['post'] ?? null, function ($query, $search) {
            $query->whereHas('post', function($qry) use($search){
                $qry->where('post_description', "LIKE", "%" . trim($search) . "%");
            });
        });

        $query->when($filters['creator'] ?? null, function ($query, $search) {
            $query->whereHas('post', function($qry) use($search){
                $qry->whereHas('user', function($q)use($search){
                    $q->whereRaw("CONCAT(first_name, isNull(last_name)) like '%" . trim($search) . "%'")
                    ->orWhere('username', "LIKE", "%" . trim($search) . "%");
                });
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
}
