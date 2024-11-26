<?php

namespace Modules\Hr\Entities;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobType extends Model
{
    use HasFactory;

      /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public static function getDropdown()
    {
        return JobType::pluck('job_title', 'id')->toArray();
    }


    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by', 'id');
    }
}
