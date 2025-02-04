<?php

namespace Modules\Sale\Entities;

use Modules\Customer\Entities\Prescription;
use Modules\Product\Entities\Product;
use Modules\AddStock\Entities\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionSellLine extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function prescription()
    {
        return $this->hasOne(Prescription::class,'sell_line_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
