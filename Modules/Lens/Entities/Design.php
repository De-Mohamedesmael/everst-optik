<?php

namespace Modules\Lens\Entities;

use Illuminate\Database\Eloquent\Model;

class Design extends Model
{
    protected $guarded = [];
    public function foci(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Focus::class, DesignFocus::class, 'design_id', 'focus_id');
    }
}
