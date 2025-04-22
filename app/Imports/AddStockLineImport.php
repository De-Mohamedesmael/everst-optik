<?php

namespace App\Imports;

use Modules\AddStock\Entities\AddStockLine;
use Modules\Setting\Entities\System;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Modules\Product\Entities\Product;


class AddStockLineImport implements ToModel, WithHeadingRow, WithValidation
{

    protected int $transaction_id;

    /**
     * Constructor
     *
     * @param int $transaction_id
     * @return void
     */
    public function __construct(int $transaction_id)
    {
        $this->transaction_id = $transaction_id;
    }


    /**
     * @param array $row
     *
     * @return void
     */
    public function model(array $row)
    {

        $product = Product::Where('products.sku', $row['product_code'])
            ->select(
                'products.id as product_id',
                'purchase_price'
            )
            ->first();


        if(empty($product)){
            print_r($row['product_code']); die();

        }
        $data = [
            'transaction_id' => $this->transaction_id,
            'product_id' => $product->product_id,
            'quantity' => $row['quantity'],
            'sell_price' => $row['sell_price'],
            'final_cost' => $row['quantity'] * $row['purchase_price'],
            'purchase_price' => $row['purchase_price'],
            'sub_total' => $row['quantity'] * $row['purchase_price']
        ];
       return AddStockLine::create($data);
    }

    public function rules(): array
    {
        return [
            'product_code' => 'required',
            'quantity' => 'required',
            'purchase_price' => 'required',
            'sell_price' => 'required',
        ];
    }

    public function uf_date($date, $time = false)
    {

        $date_format = 'm/d/Y';
        $mysql_format = 'Y-m-d';
        if ($time) {
            if (System::getProperty('time_format') == 12) {
                $date_format = $date_format . ' h:i A';
            } else {
                $date_format = $date_format . ' H:i';
            }
            $mysql_format = 'Y-m-d H:i:s';
        }
        return !empty($date_format) ? Carbon::createFromFormat($date_format, $date)->format($mysql_format) : null;
    }
    public function transformDate($value, $format = 'Y-m-d')
    {
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))->toDateString();
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }
}
