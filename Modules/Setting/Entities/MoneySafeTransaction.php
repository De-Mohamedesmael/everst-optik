<?php

namespace Modules\Setting\Entities;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Hr\Entities\JobType;

class MoneySafeTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function money_safe()
    {
        return $this->belongsTo(MoneySafe::class);
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
    public function job_type()
    {
        return $this->belongsTo(JobType::class);
    }
    public function created_by_user()
    {
        return $this->belongsTo(Admin::class, 'created_by', 'id')->withDefault(['name' => '']);
    }
}
