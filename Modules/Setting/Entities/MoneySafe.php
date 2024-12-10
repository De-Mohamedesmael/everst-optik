<?php

namespace Modules\Setting\Entities;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\AddStock\Entities\Transaction;

class MoneySafe extends Model
{
    use HasFactory,SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'add_money_admins' => 'array',
        'take_money_admins' => 'array',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function transactions(){
        return $this->hasMany(MoneySafeTransaction::class);
    }

//    public function transactions(){
//        return $this->belongsToMany(Transaction::class, 'money_safe_transactions', 'money_safe_id', 'transaction_id');
//
//    }
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
    public function created_by_user()
    {
        return $this->belongsTo(Admin::class, 'created_by', 'id')->withDefault(['name' => '']);
    }
    public function edited_by_user()
    {
        return $this->belongsTo(Admin::class, 'edited_by', 'id')->withDefault(['name' => '']);
    }
}
