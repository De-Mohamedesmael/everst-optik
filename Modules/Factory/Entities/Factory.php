<?php

namespace Modules\Factory\Entities;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Factory extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function created_by_user()
    {
        return $this->belongsTo(Admin::class, 'created_by', 'id')->withDefault(['name' => '']);
    }
    public function edited_by_user()
    {
        return $this->belongsTo(Admin::class, 'edited_by', 'id')->withDefault(['name' => '']);
    }

}
