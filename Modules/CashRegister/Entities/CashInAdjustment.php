<?php

namespace Modules\CashRegister\Entities;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Setting\Entities\Store;

class CashInAdjustment extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function cashier()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function cash_register()
    {
        return $this->belongsTo(CashRegister::class);
    }

    public function created_by_user()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
