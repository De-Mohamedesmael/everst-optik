<?php

namespace Modules\Lens\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Product;

class IndexLens extends Model
{
    protected $guarded = [];
    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class, IndexLensProduct::class, 'index_id', 'product_id');
    }
}
