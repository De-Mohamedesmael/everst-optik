<?php

namespace Modules\Setting\Entities;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Hr\Entities\Employee;

class Store extends Model
{
    use HasFactory,SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function store_pos()
    {
        return $this->hasMany(StorePos::class);
    }

    public static function getDropdown()
    {

        $employee = Employee::where('admin_id', auth('admin')->user()->id)->first();
        $stores = Store::whereIn('id', (array) $employee->store_id)
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')->toArray();

        return $stores;
    }
    public function created_by_admin()
    {
        return $this->belongsTo(Admin::class, 'created_by', 'id')->withDefault(['name' => '']);
    }
    public function deleted_by_admin()
    {
        return $this->belongsTo(Admin::class, 'deleted_by', 'id')->withDefault(['name' => '']);
    }
}
