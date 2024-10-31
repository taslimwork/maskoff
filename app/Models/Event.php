<?php

namespace App\Models;

use App\Models\Traits\HasLogo;
use App\Traits\HashTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Event extends Model
{
    use HasFactory;
    use HasSlug;
    use HashTags, HasLogo;

    protected $guarded = [];

    protected $appends = ['event_logo_url'];

    public function event_type()
    {
        return $this->belongsTo(EventType::class);
    }

    public function attendingStatus()
    {
        return $this->hasMany(EventAttendingStatus::class, 'event_id');
    }
    public function attendingUsers()
    {
        return $this->hasMany(EventAttendingStatus::class, 'event_id')->where('status', 'Attending')->with(['user' => function ($query) {
            $query->select('id','first_name', 'last_name', 'username');
        }]);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }


    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['name'] ?? null, function ($query, $search) {
            $query->where('name','like', "%" . trim($search) . "%");
        });
        $query->when($filters['organizer_name'] ?? null, function ($query, $search) {
            $query->where('organizer_name','like', "%" . trim($search) . "%");
        });

        $query->when($filters['event_date'] ?? null, function ($query, $search) {
            $date = date('Y-m-d',strtotime($search));
            $query->whereDate('event_date', '=', $date);
        });

        $query->when($filters['event_time'] ?? null, function ($query, $search) {
            $date = date('H:i',strtotime($search));
            $query->whereTime('event_time', '=', $date);
        });

        $query->when(isset($filters['active']) ?? null, function ($query, $search) use($filters){
            $query->where('active',$filters['active']);
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
}
