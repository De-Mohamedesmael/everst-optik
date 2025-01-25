<?php

namespace Modules\AddStock\Entities;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Modules\AddStockLine\Entities\ExpenseBeneficiary;
//use Modules\AddStockLine\Entities\ExpenseCategory;
//use Modules\AddStockLine\Entities\Manufacturing;
//use Modules\AddStockLine\Entities\PurchaseOrderLine;
//use Modules\AddStockLine\Entities\PurchaseReturnLine;
//use Modules\AddStockLine\Entities\RemoveStockLine;
use Modules\Setting\Entities\StorePos;
use Modules\Setting\Entities\TermsAndCondition;
use Modules\Sale\Entities\TransactionSellLine;
//use Modules\AddStockLine\Entities\TransferLine;
use Modules\Customer\Entities\Customer;
use Modules\Hr\Entities\Employee;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\Currency;
use Modules\Setting\Entities\Store;
use Modules\Setting\Entities\System;
use Modules\Setting\Entities\Tax;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Transaction extends Model  implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $appends = ['source_name'];
    protected $guarded = [];

    protected $casts = [
        'commissioned_employees' => 'array'
    ];

    public function getSourceNameAttribute()
    {
        $source_type = $this->source_type;
        $source_id = $this->source_id;
        if (empty($source_id)) {
            return '';
        }

        if ($source_type == 'pos') {
            $source = StorePos::where('id', $source_id)->first();
        }
        if ($source_type == 'admin') {
            $source = Admin::where('id', $source_id)->first();
        }
        if ($source_type == 'store') {
            $source = Store::where('id', $source_id)->first();
        }

        return $source->name ?? null;
    }
    public function manufacturing()
    {
        return $this->belongsTo(Manufacturing::class);
    }
    public function purchase_order_lines()
    {
        return $this->hasMany(PurchaseOrderLine::class);
    }

    public function add_stock_lines()
    {
        return $this->hasMany(AddStockLine::class);
    }

    public function transaction_sell_lines()
    {
        return $this->hasMany(TransactionSellLine::class);
    }
    public function transaction_sell_line()
    {
        return $this->hasOne(TransactionSellLine::class);
    }
    public function sell_return()
    {
        return $this->belongsTo(Transaction::class, 'return_parent_id', 'id');
    }

    public function parent_sale()
    {
        return $this->belongsTo(Transaction::class, 'parent_sale_id', 'id');
    }
    public function return_parent()
    {
        return $this->hasOne(Transaction::class, 'return_parent_id');
    }


    public function canceled_by_admin()
    {
        return $this->belongsTo(Admin::class, 'canceled_by', 'id')->withDefault(['name' => '']);
    }
    public function created_by_admin()
    {
        return $this->belongsTo(Admin::class, 'created_by', 'id')->withDefault(['name' => '']);
    }
    public function approved_by_admin()
    {
        return $this->belongsTo(Admin::class, 'approved_by', 'id')->withDefault(['name' => '']);
    }
    public function requested_by_admin()
    {
        return $this->belongsTo(Admin::class, 'requested_by', 'id')->withDefault(['name' => '']);
    }
    public function received_by_admin()
    {
        return $this->belongsTo(Admin::class, 'received_by', 'id')->withDefault(['name' => '']);
    }
    public function declined_by_admin()
    {
        return $this->belongsTo(Admin::class, 'declined_by', 'id')->withDefault(['name' => '']);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class)->withDefault(['employee_name' => '']);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id')->withDefault(['name' => '']);
    }



    public function add_stock_products()
    {
        return $this->hasManyThrough(Product::class, AddStockLine::class, 'transaction_id', 'id', 'id', 'product_id');
    }

    public function sell_products()
    {
        return $this->hasManyThrough(Product::class, TransactionSellLine::class, 'transaction_id', 'id', 'id', 'product_id');
    }



    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id')->withDefault(['name' => '']);
    }

    public function sender_store()
    {
        return $this->belongsTo(Store::class, 'sender_store_id')->withDefault(['name' => '']);
    }

    public function receiver_store()
    {
        return $this->belongsTo(Store::class, 'receiver_store_id')->withDefault(['name' => '']);
    }
    public function expense_category()
    {
        return $this->belongsTo(ExpenseCategory::class)->withDefault(['name' => '']);
    }
    public function expense_beneficiary()
    {
        return $this->belongsTo(ExpenseBeneficiary::class)->withDefault(['name' => '']);
    }


    public function transaction_payments()
    {
        return $this->hasMany(TransactionPayment::class);
    }

    public function add_stock_parent()
    {
        return $this->hasOne(Transaction::class, 'add_stock_id');
    }

    public function purchase_return_lines()
    {
        return $this->hasMany(PurchaseReturnLine::class);
    }

    public function transfer_lines()
    {
        return $this->hasMany(TransferLine::class);
    }

    public function remove_stock_lines()
    {
        return $this->hasMany(RemoveStockLine::class);
    }

    public function terms_and_conditions()
    {
        return $this->belongsTo(TermsAndCondition::class, 'terms_and_condition_id');
    }

    public function source()
    {
        return $this->belongsTo(Admin::class, 'source_id')->withDefault(['name' => '']);
    }
    public function tax()
    {
        return $this->belongsTo(Tax::class, 'tax_id')->withDefault(['name' => '']);
    }
    public function deliveryman()
    {
        return $this->belongsTo(Employee::class, 'deliveryman_id', 'id')->withDefault(['employee_name' => '']);
    }


    public function received_currency()
    {
        $default_currency_id = System::getProperty('currency');
        $default_currency = Currency::where('id', $default_currency_id)->first();

        return $this->belongsTo(Currency::class, 'received_currency_id')->withDefault($default_currency);
    }
    public function paying_currency()
    {
        $default_currency_id = System::getProperty('currency');
        $default_currency = Currency::where('id', $default_currency_id)->first();

        return $this->belongsTo(Currency::class, 'paying_currency_id')->withDefault($default_currency);
    }
}
