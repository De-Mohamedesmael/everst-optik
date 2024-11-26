<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Setting\Entities\Store;

class ProductStore extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function store(){
        return $this->belongsTo(Store::class);
    }



    public function product(){
        return $this->belongsTo(Product::class);
    }
}
