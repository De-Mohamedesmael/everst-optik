<?php

namespace Modules\Lens\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class Feature extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasJsonRelationships,SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $appends=['icon','icon_active','before_effect','after_effect'];


    public function getIconAttribute(){
        return $this->getFirstMediaUrl('icon')?:asset('assets/default/f_'.$this->id.'.png');
    }
    public function getIconActiveAttribute(){
        return $this->getFirstMediaUrl('icon_active')?:asset('assets/default/f_ac_'.$this->id.'.png');
    }
    public function getBeforeEffectAttribute(){
        return $this->getFirstMediaUrl('before_effect')?:asset('assets/default/'.$this->id.'before.jpg');
    }
    public function getAfterEffectAttribute(){
        return $this->getFirstMediaUrl('after_effect')?:asset('assets/default/'.$this->id.'after.jpg');
    }

    public function brand_lenses()
    {
        return $this->belongsToMany(BrandLens::class, 'feature_brand_lenses', 'feature_id', 'brand_id');
    }


}
