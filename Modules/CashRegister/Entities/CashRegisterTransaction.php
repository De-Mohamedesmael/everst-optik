<?php

namespace Modules\CashRegister\Entities;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\AddStock\Entities\Transaction;
use Modules\AddStock\Entities\TransactionPayment;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class CashRegisterTransaction extends Model implements HasMedia
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
        return $this->belongsTo(Admin::class, 'source_id');
    }
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
    public function transaction_payment()
    {
        return $this->belongsTo(TransactionPayment::class, 'transaction_payment_id');
    }
}
