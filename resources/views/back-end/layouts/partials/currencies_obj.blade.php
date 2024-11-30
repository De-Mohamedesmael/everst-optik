@php
    use Modules\Setting\Entities\ExchangeRate;$currencies_exchange_rates = ExchangeRate::leftjoin('currencies', 'exchange_rates.received_currency_id', 'currencies.id')
        ->where(function ($q) {
            $q->whereNull('expiry_date')->orWhereDate('expiry_date', '>=', date('Y-m-d'));
        })
        ->select('received_currency_id as currency_id', 'currencies.symbol', 'conversion_rate')
        ->distinct('currency_id')
        ->get();

    $currencies_obj = [];
    foreach ($currencies_exchange_rates as $currencies_exchange_rate) {
        $currencies_obj[] = ['currency_id' => $currencies_exchange_rate->currency_id, 'symbol' => $currencies_exchange_rate->symbol, 'conversion_rate' => $currencies_exchange_rate->conversion_rate, 'is_default' => 0];
    }

    $default_currency_id = \Modules\Setting\Entities\System::getProperty('currency');
    if (!empty($default_currency_id)) {
        $default_currency = Modules\Setting\Entities\Currency::where('id', $default_currency_id)
            ->select('id as currency_id', 'symbol')
            ->first();

        $d['currency_id'] = $default_currency->currency_id;
        $d['symbol'] = $default_currency->symbol;
        $d['conversion_rate'] = 1;
        $d['is_default'] = 1;
        $currencies_obj[] = $d;
    }

@endphp
<script>
    var currency_obj = <?php echo json_encode($currencies_obj); ?>;
</script>
