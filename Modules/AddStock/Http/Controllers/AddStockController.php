<?php

namespace Modules\AddStock\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\AddStock\Entities\AddStockLine;
use App\Models\Admin;
use App\Utils\CashRegisterUtil;
use App\Utils\MoneySafeUtil;
use App\Utils\NotificationUtil;
use App\Utils\ProductUtil;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use Modules\Setting\Entities\MoneySafe;
use Modules\Setting\Entities\Store;
use Modules\Setting\Entities\StorePos;

class AddStockController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected Util $commonUtil;
    protected TransactionUtil $transactionUtil;
    protected ProductUtil $productUtil;
    protected NotificationUtil $notificationUtil;
    protected CashRegisterUtil $cashRegisterUtil;
    protected MoneySafeUtil $moneysafeUtil;

    public function __construct(Util $commonUtil, ProductUtil $productUtil, TransactionUtil $transactionUtil, NotificationUtil $notificationUtil, CashRegisterUtil $cashRegisterUtil, MoneySafeUtil $moneysafeUtil)
    {
        $this->commonUtil = $commonUtil;
        $this->productUtil = $productUtil;
        $this->transactionUtil = $transactionUtil;
        $this->notificationUtil = $notificationUtil;
        $this->cashRegisterUtil = $cashRegisterUtil;
        $this->moneysafeUtil = $moneysafeUtil;

    }


    public function getSourceByTypeDropdown($type = null): string
    {
        if ($type == 'user') {
            $array = Admin::pluck('name', 'id');
        }
        if ($type == 'pos') {
            $array = StorePos::pluck('name', 'id');
        }
        if ($type == 'store') {
            $array = Store::pluck('name', 'id');
        }
        if ($type == 'safe') {
            $array = MoneySafe::pluck('name', 'id');
        }

        return $this->commonUtil->createDropdownHtml($array, __('lang.please_select'));
    }



}
