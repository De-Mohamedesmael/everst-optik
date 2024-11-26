<?php

namespace Modules\Customer\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerImportantDate extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function created_by_user()
    {
        return $this->belongsTo(Admin::class, 'created_by', 'id')->withDefault(['name' => '']);
    }
}
