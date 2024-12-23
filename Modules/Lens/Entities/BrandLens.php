<?php

namespace Modules\Lens\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class BrandLens extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, \Staudenmeir\EloquentJsonRelations\HasJsonRelationships,SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    protected $appends=['icon'];
    public function features()
    {
        return $this->belongsToMany(Feature::class, 'feature_brand_lenses', 'brand_id', 'feature_id');
    }
    public function getIconAttribute(){
        return $this->getFirstMediaUrl('icon')?:asset('assets/default/'.$this->name.'.png');
    }


}
