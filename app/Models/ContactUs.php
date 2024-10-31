<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactUs extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'contact_us';

    protected $fillable = [
        'user_id',
        'username',
        'email',
        'subject',
        'description',
        'status'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }


    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['username'] ?? null, function ($query, $search) {
            $query->where('username','like', "%" . trim($search) . "%");
        });

        $query->when($filters['email'] ?? null, function ($query, $search) {
            $query->where('email','like', "%" . trim($search) . "%");
        });

        $query->when($filters['subject'] ?? null, function ($query, $search) {
            $query->where('subject','like', "%" . trim($search) . "%");
        });

        $query->when($filters['description'] ?? null, function ($query, $search) {
            $query->where('description','like', "%" . trim($search) . "%");
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
