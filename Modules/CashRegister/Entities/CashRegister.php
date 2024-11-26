<?php

namespace Modules\CashRegister\Entities;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Setting\Entities\MoneySafe;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CashRegister extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get the Cash registers transactions.
     */
    public function cash_register_transactions()
    {
        return $this->hasMany(CashRegisterTransaction::class);
    }

    public function cashier()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function cash_given()
    {
        if ($this->source_type == 'safe') {
            return $this->belongsTo(MoneySafe::class, 'cash_given_to')->withDefault(['name' => '']);
        }
        return $this->belongsTo(Admin::class, 'cash_given_to')->withDefault(['name' => '']);
    }
}
