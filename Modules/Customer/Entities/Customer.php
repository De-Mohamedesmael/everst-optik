<?php

namespace Modules\Customer\Entities;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Hr\Entities\Employee;
use Modules\Setting\Entities\TaxLocation;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Customer extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function getGenderNameAttribute($key)
    {
       return $this->getDropdownGender()[$this->gender];
    }
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
    public function customer_important_dates()
    {
        return $this->hasMany(CustomerImportantDate::class);
    }
    public function customer_type()
    {
        return $this->belongsTo(CustomerType::class,'customer_type_id');
    }
    public function tax_location()
    {
        return $this->belongsTo(TaxLocation::class,'tax_location_id');
    }

    public function created_by_user()
    {
        return $this->belongsTo(Admin::class, 'created_by', 'id');
    }

    public static function getCustomerTreeArray()
    {
        $customer_types = CustomerType::all();

        $array = [];
        foreach ($customer_types as $type) {
            $customers = [];
            $customers = Customer::where('customer_type_id', $type->id)->get();

            $array[$type->name] = [];
            foreach ($customers as $customer) {
                $array[$type->name][$customer->id] = $customer->name;
            }
        }

        return $array;
    }

    public static function getCustomerArrayWithMobile()
    {
        $customers = Customer::select('id', 'name', 'id_number')->get();
        $customer_array = [];
        foreach ($customers as $customer) {
            $customer_array[$customer->id] = $customer->id_number . ' ' . $customer->name;
        }

        return $customer_array;
    }


    public static function getDropdownGender()
    {
        return[
            '1' => translate('Male'),
            '2' => translate('Female'),
        ];
    }
}
