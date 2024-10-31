<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Journal extends Model
{
    use HasFactory;
    use HasSlug;

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

    public function journalDetails()
    {
        return $this->HasMany(JournalDetail::class);
    }
}
