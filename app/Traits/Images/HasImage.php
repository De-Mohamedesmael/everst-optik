<?php

namespace App\Traits\Images;

use App\Models\Image;
use Illuminate\Support\Facades\Storage;

trait HasImage
{
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function getImageUrlAttribute()
    {
        if ($this->image && $this->image->storage == 'public') {

            return $this->image != null ? $this->image->url . $this->image->name : asset(self::DEFAULT);
        }

        return $this->image != null && $this->image?->path ? Storage::disk('s3')->temporaryUrl($this->image?->path, now()->addDay()) : asset(self::DEFAULT);
    }

    //    public function getLogoUrlAttribute()
    //    {
    //        if($this->image && $this->image->storage == 'public' && $this->image->type=='logo'){
    //
    //
    //            return $this->image != null ? $this->image->url . $this->image->name : asset(self::DEFAULT);
    //        }
    //
    //        return $this->image != null && $this->image?->path ? Storage::disk('s3')->temporaryUrl($this->image?->path,now()->addDay()) : asset(self::DEFAULT);
    //    }

    public function ImageResize($template = '')
    {
        return $this->image != null ? url("image/cache/$template/" . $this->image?->name) : asset(self::DEFAULT);
    }

    public function ImagesResize($template = '')
    {
        return $this->images != null ?
            collect($this->images)->each(fn ($image) => [$template ? url("image/cache/$template/" . $image?->name) : $image->url . explode('.', $image->name)[0]])
            : asset(self::DEFAULT);
    }
}
