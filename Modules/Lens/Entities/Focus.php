<?php

namespace Modules\Lens\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Product;

class Focus extends Model
{
    protected $guarded = [];
    public function designs(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Design::class, DesignFocus::class, 'focus_id', 'design_id');
    }
    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class, FocusProduct::class, 'focus_id', 'product_id');
    }
}
