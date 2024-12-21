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

    protected $appends=['icon','before_effect','after_effect'];


    public function getIconAttribute(){
        return $this->getMedia('icon')->first()?:asset('assets/website/'.$this->id.'.png');
    }
    public function getBeforeEffectAttribute(){
        return $this->getMedia('before_effect')->first()?:asset('assets/website/'.$this->id.'before.jpg');
    }
    public function getAfterEffectAttribute(){
        return $this->getMedia('after_effect')->first()?:asset('assets/website/'.$this->id.'after.jpg');
    }


}
