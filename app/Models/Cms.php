<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cms extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'text_content',
        'page_banner_image',
        'content_image1'
    ];


   /*  public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    } */

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['title'] ?? null, function ($query, $search) {
            $query->where('title','like', "%" . trim($search) . "%");
        });
    }

    public function scopeOrdering($query, array $filters)
    {
        $query->when($filters['fieldName'] ?? null, function ($query, $search) use($filters){
            $query->orderBy($search,$filters['shortBy']);
        });
    }

    public function aboutUsContent()
    {
        return $this->belongsTo(AboutUs::class, 'id', 'cms_id');
    }

}
