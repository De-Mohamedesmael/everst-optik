<?php

namespace Modules\Customer\Entities;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\AddStock\Entities\TransactionPayment;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class DebtPayment extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function source()
    {
        return $this->belongsTo(Admin::class, 'source_id')->withDefault(['name' => '']);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id')->withDefault(['name' => '']);
    }

    /**
     * Get child payments
     */
    public function child_payments()
    {
        return $this->hasMany(TransactionPayment::class, 'parent_id');
    }
    /**
     * Get child payments
     */
    public function parent_payment()
    {
        return $this->belongsTo(TransactionPayment::class, 'parent_id', 'id');
    }
    public function created_by_user()
    {
        return $this->belongsTo(Admin::class, 'created_by', 'id')->withDefault(['name' => '']);
    }
}
