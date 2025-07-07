<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table='countries';
    protected $fillable = [
        'iso', 'name','nicename','iso3','numcode','phonecode','nationality','active','is_default'
    ];

    public function scopeIsDefault($query)
    {
      return $query->where('is_default', 1);
    }

    public function isDefault()
    {
        return $this->is_default == 1;
    }

}
