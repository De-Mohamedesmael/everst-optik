<?php

namespace Modules\Setting\Entities;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StorePos extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function admin()
    {
        return $this->belongsTo(Admin::class)->withDefault(['name' => '']);
    }

    public function store()
    {
        return $this->belongsTo(Store::class)->withDefault(['name' => '']);
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
