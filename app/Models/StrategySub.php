<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class StrategySub extends Model
{
    use HasFactory, HasSlug;
    protected $guarded = [];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
    */
    protected $casts = [
        'active' => 'string',
    ];

    protected $appends = [
        'image_path'
    ];

    public function getImagePathAttribute()
    {
        return ($this->image) ?  URL::route('image', ['path' => $this->image, 'w' => 400, 'h' => 380, 'fit' => 'crop']) :  url('no-image.jpg');
    }


    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['title'] ?? null, function ($query, $search) {
            $query->where('title','like', '%' . trim($search) . '%');
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

    public function strategyType()
    {
        return $this->belongsTo(StrategyType::class);
    }
    public function strategy()
    {
        return $this->belongsTo(Strategy::class);
    }
}
