<?php

namespace Modules\Hr\Entities;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Customer\Entities\Customer;
use Modules\Product\Entities\Product;
use Modules\AddStock\Entities\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class)->withDefault(['name' => '']);
    }
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function created_by_user(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by', 'id')->withDefault(['name' => '']);
    }
}
