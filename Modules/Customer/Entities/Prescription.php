<?php

namespace Modules\Customer\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Modules\Product\Entities\Product;
use Modules\Factory\Entities\Factory;
use Modules\Setting\Entities\Store;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Prescription extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];


    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class,'product_id');
    }
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class,'store_id');
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class);
    }

    public function scopeOfFactories($query)
    {
        return $query->whereNotNull('factory_id');
    }


}
